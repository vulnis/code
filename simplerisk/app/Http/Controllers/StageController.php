<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

use App\Risk\Stage;

class StageController extends Controller
{
    protected $route = 'stages';
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->wantsJson())
        {
            return response()->json(
                Stage::all()
            );
        }
        return view($this->route,[
            'stage' => null,
            'stages' => Stage::all()
        ]);
    }

    public function show(Request $request, $id)
    {
        if ($request->wantsJson())
        {
            $item = Stage::find($id);
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
        $item = new Stage;
        $item->name = $request->name;
        $item->description = $request->description;
        $item->save();
        return redirect($this->route);
    }

}