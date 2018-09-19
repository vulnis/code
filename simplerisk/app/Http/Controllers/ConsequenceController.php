<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

use App\Consequence;

class ConsequenceController extends Controller
{
    protected $route = 'consequences';
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->wantsJson())
        {
            return response()->json(
                Consequence::all()
            );
        }
        return view($this->route,[
            'consequence' => null,
            'consequences' => Consequence::all()
        ]);
    }
    
    public function show(Request $request, $id)
    {
        if ($request->wantsJson())
        {
            $item = Consequence::find($id);
            if($item)
            {
                return response()->json($item);
            }
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
        ]);
        $item = new Consequence;
        $item->name = $request->name;
        $item->description = $request->description ? $request->description : '';
        $item->save();
        return redirect($this->route);
    }

    public function destroy(Request $request, $id)
    {
        if($id)
        {
            if ($request->wantsJson())
            {
                //Since we cannot rely on relations; check to see if a cause can be deleted.
                if(Consequence::find($id)->causes()->count() > 0)
                {
                    return response()->json(['has' => 'causes'], 400);
                }
                else
                {
                    Consequence::destroy($id);
                    return response()->json(['deleted' => $id]);
                }
            }
        }
        return redirect($this->route);
    }
}