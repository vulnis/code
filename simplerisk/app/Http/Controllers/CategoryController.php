<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Library\NameValue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

use App\Category;

class CategoryController extends Controller
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
                Category::all()
            );
        }
        return view('categories',[
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
        return redirect('/categories');
    }
}