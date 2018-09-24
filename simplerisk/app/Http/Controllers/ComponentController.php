<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Component;
use App\Category;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class ComponentController extends Controller
{
    protected $route = 'components';
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view($this->route,[
            'component' => null,
            'components' => Component::all(),
            'categories' => Category::where('type', 'Asset')->get(),
            'parents' => Component::with('children')->whereNull('parent_id')->orderBy('name', 'asc')->get(),
        ]);
    }

    public function show(Request $request, $id)
    {
        if ($request->wantsJson())
        {
            $item = Component::find($id);
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
        $item = new Component;
        $item->name = $request->name;
        $item->description = $request->description;
        $category = Category::find($request->category);
        $item->category()->associate($category);
        $item->save();
        return redirect($this->route);
    }

    public function destroy(Request $request, $id)
    {
        if($id)
        {
            if ($request->wantsJson())
            {
                Component::destroy($id);
                return response()->json(['deleted' => $id]);
            }
        }
        return redirect($this->route);
    }
}