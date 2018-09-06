<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

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
}