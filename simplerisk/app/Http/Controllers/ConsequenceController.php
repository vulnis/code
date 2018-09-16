<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

use App\Consequence;

class ConsequenceController extends Controller
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
        return view('consequence',[
            'consequence' => null
        ]);
    }
    
    public function store(Request $request)
    {
        // Validate the request...

        $this->validate($request, [
            'name' => 'required|max:255',
        ]);
        $consequence = new consequence;
        $consequence->name = $request->name;
        $consequence->description = $request->description;
        $consequence->save();
        return redirect('/consequence');
    }
}