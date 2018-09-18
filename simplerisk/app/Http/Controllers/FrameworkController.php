<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Framework;

class FrameworkController extends Controller
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

    public function show($id)
    {
        $risk = Framework::find($id);
        if($risk)
        {
            return view('framework',[
                'framework' => $framework,
                'parents' => Framework::all()
            ]);
        }
    }

    public function create()
    {
        return view('framework',[
            'framework' => null,
            'parents' => Framework::all()
        ]);
    }
    
    public function index(Request $request)
    {
        if ($request->wantsJson())
        {
            return response()->json(
                Framework::all()
            );
        }
        return view('frameworks',[
            'frameworks' => Framework::all()
        ]);
    }
    public function store(Request $request)
    {
        // Validate the request...

        $this->validate($request, [
            'name' => 'required',
        ]);
        $framework = new Framework;
        $framework->parent = $request->parent;
        $framework->name = $request->name;
        $framework->description = $request->description;
        $framework->status = $request->status;
        $framework->order = $request->order;
        $framework->last_audit_date = $request->last_audit_date;
        $framework->next_audit_date = $request->next_audit_date;
        $framework->desired_frequency = $request->desired_frequency;
        $framework->save();
        return redirect('/sources');
    }
}