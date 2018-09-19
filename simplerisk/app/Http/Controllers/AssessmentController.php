<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Assessment\Question;
use App\Risk;
use App\Assessment;
use App\Cause;
use App\Probability;
use App\Severity;
class AssessmentController extends Controller
{
    protected $route = 'assessments';
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->wantsJson())
        {
            return response()->json(
                [
                'columns' => [
                    ['field' => 'risk.subject', 'label' => trans_choice('messages.Risk',1)],
                    ['field' => 'sub_id'],
                    ['field' => 'cause.description', 'label' => trans_choice('messages.Cause',1)],
                    ['field' => 'probability.name', 'label' => trans('messages.Probability')],
                    ['field' => 'severity.name', 'label' => trans('messages.Severity')],
                    ['field' => 'level', 'label' => trans('messages.Score'), 'type' => 'number'],
                ],
                'rows' => Assessment::with(['severity', 'probability', 'cause', 'risk'])->get()
                ]
            );
        }
        return view($this->route, [
            'assessments' => Assessment::all(),
            'assessment' => null,
            'risks' => Risk::all(),
            'causes' => Cause::all(),
            'probabilities' => Probability::all(),
            'severities' => Severity::all()
            ]
        );
    }

    public function show(Request $request, $id)
    {
        if ($request->wantsJson())
        {
            $item = Assessment::find($id);
            if($item)
            {
                return response()->json($item);
            }
        }
    }

    public function store(Request $request)
    {
        $item = new Assessment;
        $risk = Risk::find($request->risk);
        $item->risk()->associate($risk);
        $cause = Cause::find($request->cause);
        $item->cause()->associate($cause);
        $severity = Severity::find($request->severity);
        $item->severity()->associate($severity);
        $probability = Probability::find($request->probability);
        $item->probability()->associate($probability);
        $item->save();
        return redirect($this->route);
    }

}
