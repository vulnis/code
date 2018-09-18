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
    /**
     * Create a new controller instance.
     *
     * @return void
     */
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
            'types' => [
                new NameValue(trans_choice('messages.Risk',1), 'Risk'),
                new NameValue(trans_choice('messages.Cause',1), 'Cause')
            ]
        ]);
    }
    
    public function create()
    {
        return view('category',[
            'category' => null,
            'types' => [
                new NameValue(trans_choice('messages.Risk',1), 'Risk'),
                new NameValue(trans_choice('messages.Cause',1), 'Cause')
            ]
        ]);
    }
    
    public function store(Request $request)
    {
        // Validate the request...

        $this->validate($request, [
            'name' => 'required|max:50',
        ]);
        $category = new Category;
        $category->type = $request->type;
        $category->name = $request->name;
        $category->save();
        return redirect('/categories');
    }
}