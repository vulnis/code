<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Library\NameValue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

use App\Category;
use App\Risk;
use App\Cause;

class CategoryController extends Controller
{
    protected $route = 'categories';
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->wantsJson())
        {
            return response()->json(
                Category::all()
            );
        }
        return view($this->route,[
            'categories' => Category::all(),
            'category' => null,
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
            $item = Category::find($id);
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
        $item = new Category;
        $item->type = $request->type;
        $item->name = $request->name;
        $item->save();
        if ($request->wantsJson())
        {
            return response()->json($item);
        }
        return redirect($this->route);
    }

    public function destroy(Request $request, $id)
    {
        if($id)
        {
            if ($request->wantsJson())
            {
                //Since we cannot rely on relations; check to see if a category can be deleted.
                if(Cause::where('category_id', $id)->count() > 0)
                {
                    return response()->json(['has' => 'cause'], 400);
                }
                elseif(Risk::where('category', $id)->count() > 0)
                {
                    return response()->json(['has' => 'risk'], 400);
                }
                else
                {
                    Category::destroy($id);
                    return response()->json(['deleted' => $id]);
                }
            }
        }
        return redirect($this->route);
    }
}