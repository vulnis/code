<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Assessment;
use App\Assessment\Question;
use App\Hazard;
use App\Sra;
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


    public function create()
    {
        // Hazards
        // Root causes
        // Probability
        // Severity
        // Risk level

        return view('sra',[
            'sra' => null,
            'hazards' => Hazard::all(),
            'causes' => Cause::all(),
            'probabilities' => Probability::all(),
            'severities' => Severity::all()
        ]);
    }

    public function show($id){
        $sra = Sra::find($id);
        if($sra)
        {
            return view('sra',[
                'sra' => $sra,
                'hazards' => Hazard::all(),
                'causes' => Cause::all(),
                'probabilities' => Probability::all(),
                'severities' => Severity::all()
            ]);
        }
    }
    public function store(Request $request)
    {
        $assessment = new Sra;
        $hazard = Hazard::find($request->hazard);
        $assessment->hazard()->associate($hazard);
        $cause = Cause::find($request->cause);
        $assessment->cause()->associate($cause);
        $severity = Severity::find($request->severity);
        $assessment->severity()->associate($severity);
        $probability = Probability::find($request->probability);
        $assessment->probability()->associate($probability);
        $assessment->save();
        return redirect('/assessment');
    }

}
