<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Repositories\AssessmentRepository;
use App\Question;
use App\Assessment;
class AssessmentController extends Controller
{
    protected $assessments;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AssessmentRepository $assessments)
    {
        $this->middleware('auth');
        $this->assessments = $assessments;
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

    public function newindex($id = 0, $query = null)
    {
        if($id > 0){
            $assessment = Assessment::find($id);
            $whereclause = array();
            $whereclause[] = [ 'assessment_id', '=', $id];

            if($query){
                $whereclause[] = ['question', 'like', '%' . $query . '%'];
            }

            $out = Question::where($whereclause)->orderBy('order')->get();

            return view('assessment.index',[
                'prefix' => 'assessments',
                'menu' => $this->getSideMenu(),
                'questions' => $out,
                'assessment' => $assessment,
                'filter' => $query
            ]);
        } else {
            return view('assessment.index',[
                'prefix' => 'assessments',
                'menu' => $this->getSideMenu(),
                'assessments' => $this->assessments->getAssessments(),
            ]);
        }
        
    }

    public function index(Request $request)
    {
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
