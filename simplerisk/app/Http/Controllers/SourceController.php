<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Library\NameValue;
use Illuminate\Support\Facades\Auth;
use App\Source;
use App\Risk;
class SourceController extends Controller
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
                Source::all()
            );
        }
        return view('sources',[
            'sources' => Source::all(),
            'source' => null,
            'types' => [
                new NameValue(trans_choice('messages.Risk',1), 'Risk'),
                new NameValue(trans_choice('messages.Cause',1), 'Cause')
            ]
        ]);
    }

    public function show(Request $request, $id)
    {
        if ($request->wantsJson())
        {
            $item = Source::find($id);
            if($item)
            {
                return response()->json($item);
            }
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
        ]);
        $item = new source;
        $item->type = $request->type;
        $item->name = $request->name;
        $item->save();
        return redirect('sources');
    }

    public function destroy(Request $request, $id)
    {
        if($id)
        {
            if ($request->wantsJson())
            {
                //Since we cannot rely on relations; check to see if a category can be deleted.
                if(Risk::where('source', $id)->count() > 0)
                {
                    return response()->json(['has' => 'risk'], 400);
                }
                else
                {
                    Source::destroy($id);
                    return response()->json(['deleted' => $id]);
                }
            }
        }
        return redirect($this->route);
    }
}