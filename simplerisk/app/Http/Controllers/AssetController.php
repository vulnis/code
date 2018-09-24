<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Asset;
use Illuminate\Support\Facades\Auth;

class AssetController extends Controller
{
    protected $route = 'assets';
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view($this->route,[
            'asset' => null,
            'assets' => Asset::all(),
        ]);
    }

    public function show(Request $request, $id)
    {
        if ($request->wantsJson())
        {
            $item = Asset::find($id);
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
        $item = new Asset;
        $item->name = $request->name;
        $item->details = $request->description;
        $item->location = $request->location ? $request->location : 0;
        $item->team = $request->team ? $request->team : 0;
        $item->save();
        return redirect($this->route);
    }

    public function destroy(Request $request, $id)
    {
        if($id)
        {
            if ($request->wantsJson())
            {
                Asset::destroy($id);
                return response()->json(['deleted' => $id]);
            }
        }
        return redirect($this->route);
    }
}