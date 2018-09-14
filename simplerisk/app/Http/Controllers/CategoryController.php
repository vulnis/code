<?php

namespace App\Http\Controllers;

use App\Http\Requests;
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

    
    public function new()
    {
        return view('category',[
            'category' => null,
            'types' => [
                new valName(trans('messages.Risk'), 'Risk'),
                new valName(trans('messages.Hazard'), 'Hazard'),
                new valName(trans('messages.Cause'), 'Cause')
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

class valName {

    public $name;
    public $value;

    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }
}