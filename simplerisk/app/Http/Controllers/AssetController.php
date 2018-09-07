<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Asset;
use Illuminate\Support\Facades\Auth;
class AssetController extends Controller
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
        $assets = Asset::orderBy('submission_date', 'asc')->get();
        return view('assets.index',[
            'prefix' => 'Assets',
            'Assets' => $assets
        ]);
    }

    public function new()
    {
        return view('assets.new',[
            'prefix' => 'assets'
        ]);
    }

    public function store(Request $request)
    {
        // Validate the request...
        $this->validate($request, [
            'subject' => 'required|max:300',
        ]);
        $asset = new Asset;
        $asset->subject = $request->subject;
        $asset->submitted_by = Auth::user()->value;
        $asset->save();
        return redirect('/assets');
    }
}