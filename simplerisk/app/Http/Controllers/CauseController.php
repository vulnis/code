<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

use App\Category;
use App\Consequence;
use App\Cause;
use App\Source;
class CauseController extends Controller
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
                Cause::all()
            );
        }
        return view('causes',[
            'causes' => Cause::all(),
            'cause' => null,
            'categories' => Category::where('type', 'Cause')->get(),
            'consequences' => $consequences = Consequence::all()
        ]);
    }

    public function show(Request $request, $id)
    {
        if ($request->wantsJson())
        {
            $item = Cause::find($id);
            if($item)
            {
                return response()->json($item);
            }
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'description' => 'required',
        ]);
        $item = new Cause;
        $item->description = $request->description;
        $category = Category::find($request->category);
        $item->category()->associate($category);
        $consequence_array = [];

        if($request->consequence)
        {
            foreach ($request->consequence as $c){
                $consequence_array[] = (int)$c;
            }
        }

        $item->save();
        $item->consequences()->sync($consequence_array);
        
        return redirect('/causes');
    }
}
