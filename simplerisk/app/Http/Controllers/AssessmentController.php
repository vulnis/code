<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

use App\Assessment;
use App\Assessment\Question;

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

}