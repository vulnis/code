<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

use App\Test;

class TestController extends Controller
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

    public function index()
    {
        $tests = Test::orderBy('created_at', 'asc')->get();
        return view('tests',[
            'prefix' => 'tests',
            'tests' => $tests
        ]);
    }

    public function new()
    {
        $categories = Category::all();
        $impacts = Impact::all();
        $probabilities = Probability::all();
        $sources = Source::all();

        return view('tests',[
            'risk' => null,
            'categories' => $categories,
            'impacts' => $impacts,
            'sources' => $sources,
            'probabilities' => $probabilities,
        ]);
    }
    
    public function detail($id)
    {
        $risk = Risk::find($id);
        $categories = Category::all();
        $impacts = Impact::all();
        $probabilities = Probability::all();
        $sources = Source::all();
        if($risk)
        {
            return view('test',[
                'risk' => $risk,
                'categories' => $categories,
                'impacts' => $impacts,
                'sources' => $sources,
                'probabilities' => $probabilities,
            ]);
        }
        return redirect('/compliance/index.php');
        
    }

    public function store(Request $request)
    {
        // Validate the request...
        $this->validate($request, [
            'subject' => 'required|max:300',
        ]);
        $risk = new Risk;
        
        $risk->subject = $request->subject;
        $risk->category = $request->category;
        $risk->source = $request->source;
        $risk->status = 'New';
        $risk->notes = $request->notes;
        $risk->submitted_by = Auth::user()->value;
        $risk->save();

        // Calculate the score based on the method
        $riskCalculator = new RiskCalculator();
        $score = new Score;
        $score->id = $risk->id;
        $score->scoring_method = $request->method;
        $score->calculated_risk = $riskCalculator->getRiskScore($request->likelihood, $request->impact, $request->method);
        $score->ClASSIC_likelihood = $request->likelihood;
        $score->ClASSIC_impact = $request->impact;
        $score->save();
        return redirect('/compliance/index.php');
    }
}
