<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

use App\Category;
use App\Consequence;
use App\Cause;

class CauseController extends Controller
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

    public function create()
    {
        $categories = Category::where('type', 'Cause')->get();
        $consequences = Consequence::all();
        return view('cause',[
            'cause' => null,
            'categories' => $categories,
            'consequences' => $consequences
        ]);
    }

    public function show($cause)
    {
        $categories = Category::where('type', 'Cause')->get();
        $consequences = Consequence::all();
        return view('cause',[
            'cause' => Cause::find($cause),
            'categories' => $categories,
            'consequences' => $consequences
        ]);
    }
    
    public function index(Request $request)
    {
        if ($request->wantsJson())
        {
            return response()->json(
                Cause::all()
            );
        }
        return view('causes',[
            'causes' => Cause::all()
        ]);
    }

    public function store(Request $request)
    {
        // Validate the request...

        $this->validate($request, [
            'description' => 'required',
        ]);
        $cause = new Cause;
        $cause->description = $request->description;
        $category = Category::find($request->category);
        $cause->category()->associate($category);
        $consequence_array = [];

        if($request->consequence)
        {
            foreach ($request->consequence as $c){
                $consequence_array[] = (int)$c;
                // Code Here
            }
        }

        $cause->save();
        $cause->consequences()->sync($consequence_array);
        

        return redirect('/causes');
    }
}
