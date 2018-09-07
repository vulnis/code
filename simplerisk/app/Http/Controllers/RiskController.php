<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Risk;
use Illuminate\Support\Facades\Auth;
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
        return view('risks.index',[
            'prefix' => 'risks',
            'risks' => $risks
        ]);
    }

    public function new()
    {
        return view('risks.new',[
            'prefix' => 'risks'
        ]);
    }

    public function store(Request $request)
    {
        // Validate the request...
        $this->validate($request, [
            'subject' => 'required|max:300',
        ]);
        $risk = new Risk;
        $risk->subject = $request->subject;
        $risk->submitted_by = Auth::user()->value;
        $risk->save();
        return redirect('/risks');
    }
}