<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use App\Mitigation;

class MitigationController extends Controller
{
    protected $route = 'mitigations';
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'description' => 'required',
        ]);
        $item = new Mitigation;
        $item->risk_id = 0;
        $item->planning_strategy = 0;
        $item->mitigation_effort = 0;
        $item->mitigation_owner = $request->owner ? $request->owner : Auth::user()->value;
        $item->submitted_by = Auth::user()->value;
        $item->current_solution = $request->description ? $request->description : '';
        $item->security_requirements = '';
        $item->security_recommendations = '';
        $planning_date = $request->planning_date ? new Carbon($request->planning_date) : Carbon::now()->addDays(7);
        $item->planning_date = $planning_date;
        $item->mitigation_percent = 0;
        if($request->reassessment <> -1)
        {
            switch ($request->reassessment)
            {
                case "7 days":
                    $item->reassessment = $planning_date->addDays(7);
                    break;
                case "14 days":
                    $item->reassessment = $planning_date->addDays(14);
                    break;
                case "4 weeks":
                    $item->reassessment = $planning_date->addWeeks(4);
                    break;
                case "1 month":
                    $item->reassessment = $planning_date->addMonths(1);
                    break;
                case "2 months":
                    $item->reassessment = $planning_date->addMonths(2);
                    break;
                case "1 year":
                    $item->reassessment = $planning_date->addYears(1);
                    break;
                // In all other cases, the value is incorrect, we do nothing to the reassessment date
             }
        }

        $item->mitigation_team = $request->responsible;
        $item->assessment_id = $request->id;
        $item->type = $request->type;
        $item->report = $request->report ? $request->report : 0;
        $item->authorities = $request->authorities ? $request->authorities : 0;
        $item->save();
        if ($request->wantsJson())
        {
            return response()->json(['created' => $item]);

        }
        return redirect($this->route);
    }

    public function destroy(Request $request, $id)
    {
        if($id)
        {
            if ($request->wantsJson())
            {
                Mitigation::destroy($id);
                return response()->json(['deleted' => $id]);

            }
        }
        return redirect($this->route);
    }
}
