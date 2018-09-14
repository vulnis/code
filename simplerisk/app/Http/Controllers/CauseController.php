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

    public function new()
    {
        $categories = Category::where('type', 'Cause')->get();
        $consequences = Consequence::all();
        return view('cause',[
            'cause' => null,
            'categories' => $categories,
            'consequences' => $consequences
        ]);
    }
    
    public function store(Request $request)
    {
        // Validate the request...

        $this->validate($request, [
            'description' => 'required',
        ]);
        var_dump($request->consequence);
        $cause = new Cause;
        $cause->description = $request->description;
        $cause->order = $request->order;
        $cause->category = $request->category;
        $consequence_array = [];
        foreach ($request->consequence as $c){
            $consequence_array[] = (int)$c;
            // Code Here
        }
        $cause->save();
        $cause->consequences()->sync($consequence_array);
        

        return redirect('/hazards');
    }
}
