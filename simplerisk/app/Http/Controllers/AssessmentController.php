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

    public function newindex($id = 0, $query = null, $json = false)
    {
        if($id > 0){
            $assessment = Assessment::find($id);
            $whereclause = array();
            $whereclause[] = [ 'assessment_id', '=', $id];

            if($query){
                $whereclause[] = ['question', 'like', '%' . $query . '%'];
            }

            $out = Question::where($whereclause)->orderBy('order')->get();
            if($json){
                return $out;
            }
            return view('assessment.index',[
                'prefix' => 'assessments',
                'menu' => $this->getSideMenu(),
                'questions' => $out,
                'assessment' => $assessment,
                'filter' => $query
            ]);
        } else {
            if($json){
                return Assessment::all();
            }
            return view('assessment.index',[
                'prefix' => 'assessments',
                'menu' => $this->getSideMenu(),
                'assessments' => Assessment::all(),
            ]);
        }
        
    }

    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            // Define the response
            $response = [
                'errors' => 'Sorry, something went wrong.'
            ];
            return response()->json($this->newindex($request->input('id'),  $request->input('q'), true), 200);
        }
        return $this->newindex($request->input('id'),  $request->input('q'));
    }


    public function new()
    {
        return view('assessment.new',[
            'prefix' => 'assesments'
        ]);
    }

    public function store(Request $request)
    {
        // Validate the request...
        $this->validate($request, [
            'subject' => 'required|max:300',
        ]);
        $assessment = new Assessment;
        $assessment->subject = $request->subject;
        $assessment->submitted_by = Auth::user()->value;
        $assessment->save();
        return redirect('/assessments');
    }

    public function indexSra()
    {
        return view('sras',[]);
    }

    public function newSra()
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

    public function detailSra($id){
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
    public function storeSra(Request $request)
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
        return redirect('/assessments');
    }

}
