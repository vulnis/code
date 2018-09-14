<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

use App\Hazard;
use App\Category;
use App\Source;
use App\Hazard\Stage;

class HazardController extends Controller
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
        return view('hazards',[
            'hazards' => Hazard::all()
        ]);
    }

    public function detail($id)
    {
        $hazard = Hazard::find($id);
        if($hazard)
        {
            return view('hazard',[
                'hazard' => $hazard,
                'categories' => Category::where('type', 'hazard')->get(),
                'stages' => Stage::all(),
                'sources' => Source::where('type','Hazard')->get()
            ]);
        }
    }

    public function new()
    {
        return view('hazard',[
            'hazard' => null,
            'categories' => Category::where('type', 'Hazard')->get(),
            'stages' => Stage::all(),
            'sources' => Source::where('type', 'Hazard')->get()
        ]);
    }
    
    public function store(Request $request)
    {
        // Validate the request...

        $this->validate($request, [
            'name' => 'required|max:200',
        ]);
        $hazard = new Hazard;
        $hazard->name = $request->name;
        $hazard->description = $request->description;
        $hazard->category = $request->category;
        $hazard->stage = $request->stage;
        $hazard->source = $request->source;
        $hazard->save();

        return redirect('/hazards');
    }
}
