<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Library\NameValue;
use Illuminate\Support\Facades\Auth;

use App\Source;

class SourceController extends Controller
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
        return view('source',[
            'source' => null,
            'types' => [
                new NameValue(trans('messages.Risk'), 'Risk'),
                new NameValue(trans('messages.Hazard'), 'Hazard'),
                new NameValue(trans('messages.Cause'), 'Cause')
            ]
        ]);
    }
    
    public function index(Request $request)
    {
        if ($request->wantsJson())
        {
            return response()->json(
                Source::all()
            );
        }
        return view('sources',[
            'sources' => Source::all(),
            'types' => [
                new NameValue(trans('messages.Risk'), 'Risk'),
                new NameValue(trans('messages.Hazard'), 'Hazard'),
                new NameValue(trans('messages.Cause'), 'Cause')
            ]
        ]);
    }

    public function store(Request $request)
    {
        // Validate the request...

        $this->validate($request, [
            'name' => 'required|max:50',
        ]);
        $source = new source;
        $source->type = $request->type;
        $source->name = $request->name;
        $source->save();
        return redirect('/sources');
    }
}