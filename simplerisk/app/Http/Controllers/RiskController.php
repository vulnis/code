<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

use App\Risk;
use App\Category;
use App\Risk\Impact;
use App\Risk\Score;
use App\Source;
use App\Probability;

use App\Library\RiskCalculator;

class RiskController extends Controller
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
        $risks = Risk::orderBy('submission_date', 'asc')->get();
        return view('risks',[
            'prefix' => 'risks',
            'risks' => $risks
        ]);
    }

    public function new()
    {
        $categories = Category::where('type', 'Risk')->get();
        $impacts = Impact::all();
        $probabilities = Probability::all();
        $sources = Source::where('type', 'Risk')->get();

        return view('risk',[
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
        if($risk)
        {
            return view('risk',[
                'risk' => $risk,
                'categories' => Category::where('type', 'Risk')->get(),
                'impacts' => Impact::all(),
                'sources' => Source::where('type', 'Risk')->get(),
                'probabilities' => Probability::all(),
            ]);
        }
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
        return redirect('/management/index.php');
    }
}
