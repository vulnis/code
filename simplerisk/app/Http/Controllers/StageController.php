<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

use App\Risk\Stage;

class StageController extends Controller
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
                Stage::all()
            );
        }
        return view('stages',[
            'stages' => Stage::all()
        ]);
    }

    public function store(Request $request)
    {
        // Validate the request...

        $this->validate($request, [
            'name' => 'required|max:50',
        ]);
        $stage = new Stage;
        $stage->name = $request->name;
        $stage->description = $request->description;
        $stage->save();
        return redirect('/stages');
    }

    public function create()
    {
        return view('stage',[
            'stage' => null
        ]);
    }

    public function show($stage)
    {
        return view('stage',[
            'stage' => Stage::find($stage)
        ]);
    }

}