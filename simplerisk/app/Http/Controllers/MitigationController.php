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
        $planning_date = Carbon::now()->addDays(14); //two weeks notice
        $item = new Mitigation;
        $item->risk_id = 0;
        $item->planning_strategy = 0;
        $item->mitigation_effort = 0;
        $item->mitigation_owner = $request->owner ? $request->owner : Auth::user()->value;
        $item->submitted_by = Auth::user()->value;
        $item->current_solution = $request->description ? $request->description : '';
        $item->security_requirements = '';
        $item->security_recommendations = '';
        $item->planning_date = $planning_date;
        $item->mitigation_percent = 0;
        $item->planning_date = $planning_date->addMonths(1); //
        $item->mitigation_team = $request->responsible;
        $item->assessment_id = $request->id;
        $item->type = $request->type;
        $item->report = $request->report ? $request->report : 0;
        $item->authorities = $request->authorities ? $request->authorities : 0;
        $item->save();
        if ($request->wantsJson())
        {
            return response()->json(['created' => $item->id]);

        }
        return redirect($this->route);
    }
}
