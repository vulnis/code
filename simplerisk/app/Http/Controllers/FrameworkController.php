<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Framework;

class FrameworkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
            'framework' => null,
            'parents' => Framework::all(),
            'frameworks' => Framework::all()
        ]);
    }

    public function show(Request $request, $id)
    {
        if ($request->wantsJson())
        {
            $item = Framework::find($id);
            if($item)
            {
                return response()->json($item);
            }
        }
    }

    public function store(Request $request)
    {
        // Validate the request...

        $this->validate($request, [
            'name' => 'required',
        ]);
        $item = new Framework;
        $parent = Framework::find($request->parent);
        $item->parent()->associate($parent);
        $item->name = $request->name;
        $item->description = $request->description;
        $item->status = $request->status;
        $item->order = $request->order;
        $item->last_audit_date = $request->last_audit_date;
        $item->next_audit_date = $request->next_audit_date;
        $item->desired_frequency = $request->desired_frequency;
        $item->save();
        return redirect('/frameworks');
    }
}