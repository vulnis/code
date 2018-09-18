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
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Generate an array that will be used to generate the side menu
     */
    private function getSideMenu(){
        $menu = array(
            array(trans('messages.AvailableAssessments'), 'index.php'),
            array(trans('messages.PendingRisks'), 'risks.php')
        );
        return $menu;
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
        return view('assessments', [
            'assessments' => Assessment::all()
            ]
        );
    }

    public function create()
    {
        // Root causes
        // Probability
        // Severity
        // Risk level

        return view('assessment',[
            'assessment' => null,
            'risks' => Risk::all(),
            'causes' => Cause::all(),
            'probabilities' => Probability::all(),
            'severities' => Severity::all()
        ]);
    }

    public function show($id){
        $assessment = Assessment::find($id);
        if($assessment)
        {
            return view('assessment',[
                'assessment' => $assessment,
                'risks' => Risk::all(),
                'causes' => Cause::all(),
                'probabilities' => Probability::all(),
                'severities' => Severity::all()
            ]);
        }
    }
    public function store(Request $request)
    {
        $assessment = new Assessment;
        $risk = Risk::find($request->risk);
        $assessment->risk()->associate($risk);
        $cause = Cause::find($request->cause);
        $assessment->cause()->associate($cause);
        $severity = Severity::find($request->severity);
        $assessment->severity()->associate($severity);
        $probability = Probability::find($request->probability);
        $assessment->probability()->associate($probability);
        $assessment->save();
        return redirect('/assessments');
    }

}
