<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

use App\Risk;
use App\Category;
use App\Source;
use App\Risk\Stage;

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

    public function index(Request $request)
    {
        if ($request->wantsJson())
        {
            return response()->json(
                Risk::all()
            );
        }
        return view('risks',[
            'risks' => Risk::all()
        ]);
    }

    public function show($id)
    {
        $risk = Risk::find($id);
        if($risk)
        {
            return view('risk',[
                'risk' => $risk,
                'categories' => Category::where('type', 'risk')->get(),
                'stages' => Stage::all(),
                'sources' => Source::where('type','Risk')->get()
            ]);
        }
    }

    public function create()
    {
        return view('risk',[
            'risk' => null,
            'categories' => Category::where('type', 'Risk')->get(),
            'stages' => Stage::all(),
            'sources' => Source::where('type', 'Risk')->get()
        ]);
    }
    
    public function store(Request $request)
    {
        // Validate the request...

        $this->validate($request, [
            'name' => 'required|max:200',
        ]);
        $risk = new Risk;
        $risk->name = $request->name;
        $risk->description = $request->description;
        $category = Category::find($request->category);
        $risk->category()->associate($category);
        $stage = Stage::find($request->stage);
        $risk->stage()->associate($stage);
        $source = Source::find($request->source);
        $risk->source()->associate($source);
        $risk->save();

        return redirect('/risks');
    }
}
