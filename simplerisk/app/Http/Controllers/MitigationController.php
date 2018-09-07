<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mitigation;
use Illuminate\Support\Facades\Auth;
class MitigationController extends Controller
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
        $mitigations = Mitigation::orderBy('submission_date', 'asc')->get();
        return view('mitigations.index',[
            'prefix' => 'mitigations',
            'mitigations' => $mitigations
        ]);
    }

    public function new()
    {
        return view('mitigations.new',[
            'prefix' => 'mitigations'
        ]);
    }

    public function store(Request $request)
    {
        // Validate the request...
        $this->validate($request, [
            'subject' => 'required|max:300',
        ]);
        $mitigation = new Mitigation;
        $mitigation->subject = $request->subject;
        $mitigation->submitted_by = Auth::user()->value;
        $mitigation->save();
        return redirect('/mitigations');
    }
}