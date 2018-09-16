<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Risk;
use App\Risk\Closure;
class RiskRepository
{
    /**
     * Get the risk count by status.
     *
     * @return Collection
     */
    public function byStatus()
    {
        return DB::table('risks')
            ->select(DB::raw('count(*) as risks, status as name'))
            ->groupBy('name')
            ->get();
    }

    /**
     * Get the risk count by open or closed. Add a color to the collection for display purposes.
     *
     * @return Collection
     */
    public function closedOrNot()
    {
        return DB::table('risks')
            ->select(DB::raw('count(*) as risks, CASE WHEN status = "Closed" THEN "'. trans('messages.Closed') . 
                '" ELSE "' . trans('messages.Open') . '" END as name,' . 
                ' CASE WHEN status = "Closed" THEN "#00ff00" ELSE "#ff0000" END as color'))
            ->groupBy('name')
            ->get();
    }

    /**
     * Get the risk count for mitigated. Add a color to the collection for display purposes.
     *
     * @return Collection
     */
    public function mitigatedOrNot()
    {
        return DB::table('risks')
            ->select(DB::raw('count(*) as risks, CASE WHEN mitigation_id > 0 THEN "'. trans('messages.Mitigated') . 
                '" ELSE "' . trans('messages.Unmitigated') . '" END as name,' . 
                ' CASE WHEN mitigation_id > 0 THEN "#00ff00" ELSE "#ff0000" END as color'))
            ->groupBy('name')
            ->get();
    }

    /**
     * Get the risk count for mitigated. Add a color to the collection for display purposes.
     *
     * @return Collection
     */
    public function reviewedOrNot()
    {
        return DB::table('risks')
            ->select(DB::raw('count(*) as risks, CASE WHEN mgmt_review > 0 THEN "'. trans('messages.Reviewed') . 
                '" ELSE "' . trans('messages.Unreviewed') . '" END as name,' . 
                ' CASE WHEN mgmt_review > 0 THEN "#00ff00" ELSE "#ff0000" END as color'))
            ->groupBy('name')
            ->get();
    }

    private function getDateArray($array, $timeframe){
        $items = [];
        // For each row
        foreach ($array as $key=>$row)
        {
            // If the timeframe is by day
            if ($timeframe === "day")
            {
                // Set the date to the day
                $date = date('Y-m-d', strtotime($row['date']));
            }
            // If the timeframe is by month
            else if ($timeframe === "month")
            {
                // Set the date to the month
                $date = date('Y-m', strtotime($row['date']));
            }
            // If the timeframe is by year
            else if ($timeframe === "year")
            {
                // Set the date to the year
                $date = date('Y', strtotime($row['date']));
            }
            
            // If the date is different from the current date
            if(isset($items[$date])){
                $items[$date] = $items[$date] + 1;
            } else {
                $items[$date] = 1;
            }
        }

        // Return the open date array
        return $items;
    }
    /**
     * Taken from reporting.php -> get_closed_risks_array
     */
    public function getClosedRisks($timeframe)
    {
        $array = Closure::select('id','closure_date as date')
            ->orderBy('closure_date')
            ->get()->toArray();
        return $this->getDateArray($array, $timeframe);
        
    }
    /**
     * Taken from reporting.php -> get_opened_risks_array
     */
    public function getOpenedRisks($timeframe)
    {
        $array = Risk::select('id','submission_date as date')
            ->orderBy('submission_date')
            ->get()->toArray();
        return $this->getDateArray($array, $timeframe);
    }

    function risksByMonth()
    {
        $months = [];
        
        // Get the opened risks array by month
        $open = $this->getOpenedRisks("month");
        $closed = $this->getClosedRisks("month");
        // For each of the past 12 months
        for ($i=11; $i>=0; $i--)
        {
            // Get the month
            $month = date('Y-m', strtotime("first day of -$i month"));
            $months[$month] = [
                'open' => 0,
                'closed' => 0,
                'trend' => 0,
                'total' => 0
            ];
            if(isset($open[$month]))
            {
                $months[$month]['open'] = $open[$month];
            };
            if(isset($closed[$month]))
            {
                $months[$month]['closed'] = $closed[$month];
            };
            $months[$month]['trend'] = $months[$month]['open'] - $months[$month]['closed'];
        }
        return $months;
    }

}