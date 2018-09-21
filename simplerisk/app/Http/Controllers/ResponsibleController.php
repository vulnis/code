<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Responsible;
use App\Mitigation;
use App\Asset;
use Illuminate\Support\Facades\Auth;

class ResponsibleController extends Controller
{
    protected $route = 'responsibles';
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view($this->route,[
            'responsible' => null,
            'responsibles' => Responsible::all()
        ]);
    }

    public function show(Request $request, $id)
    {
        if ($request->wantsJson())
        {
            $item = Responsible::find($id);
            if($item)
            {
                return response()->json($item);
            }
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:200',
        ]);
        $item = new Responsible;
        $item->name = $request->name;
        $item->save();
        return redirect($this->route);
    }

    public function destroy(Request $request, $id)
    {
        if($id)
        {
            if ($request->wantsJson())
            {
                // Check relation to asset
                // Check relation to mitigation
                if(Mitigation::where('mitigation_team', $id)->count() > 0)
                {
                    return response()->json(['has' => 'mitigation'], 400);
                }
                elseif(Asset::where('team', $id)->count() > 0)
                {
                    return response()->json(['has' => 'team'], 400);
                }
                else
                {
                    Responsible::destroy($id);
                    return response()->json(['deleted' => $id]);
                }
            }
        }
        return redirect($this->route);
    }
}