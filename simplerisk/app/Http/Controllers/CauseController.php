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
use App\Assessment;
use App\Component;
class CauseController extends Controller
{
    protected $route = 'causes';
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
        return view($this->route,[
            'causes' => Cause::all(),
            'cause' => null,
            'categories' => Category::where('type', 'Cause')->get(),
            'components' => Component::all(),
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
        
        return redirect($this->route);
    }

    public function destroy(Request $request, $id)
    {
        if($id)
        {
            if ($request->wantsJson())
            {
                //Since we cannot rely on relations; check to see if a cause can be deleted.
                if(Assessment::where('cause_id', $id)->count() > 0)
                {
                    return response()->json(['has' => 'assessment'], 400);
                }
                else
                {
                    // Cascade delete consequences
                    Cause::find($id)->consequences()->sync([]);
                    Cause::destroy($id);
                    return response()->json(['deleted' => $id]);
                }
            }
        }
        return redirect($this->route);
    }
}
