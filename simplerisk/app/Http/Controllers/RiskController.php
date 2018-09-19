<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

use App\Risk;
use App\Category;
use App\Source;
use App\Risk\Stage;

class RiskController extends Controller
{
    protected $route = 'risks';
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->wantsJson())
        {
            return response()->json(
                Risk::all()
            );
        }
        return view($this->route,[
            'risk' => null,
            'risks' => Risk::all(),
            'categories' => Category::where('type', 'risk')->get(),
            'stages' => Stage::all(),
            'sources' => Source::where('type','Risk')->get()
        ]);
    }

    public function show(Request $request, $id)
    {
        if ($request->wantsJson())
        {
            $item = Risk::find($id);
            if($item)
            {
                return response()->json($item);
            }
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'subject' => 'required|max:200',
        ]);
        $item = new Risk;
        $item->subject = $request->subject;
        $item->notes = $request->description ? $request->description : '';
        //$category = Category::find($request->category);
        $item->category = $request->category;
        //$stage = Stage::find($request->stage);
        //$item->stage()->associate($stage);
        $source = Source::find($request->source);
        //$item->source()->associate($source);
        $item->status = $request->status ? $request->status : 'New';
        $item->location = $request->location ? $request->location : 0;
        $item->technology = $request->technology ? $request->technology : 0;
        $item->owner = $request->owner ? $request->owner : Auth::user()->value;
        $item->manager = $request->manager ? $request->manager : 0;
        $item->assessment = $request->assessment ? $request->assessment : '';
        $item->additional_stakeholders = $request->additional_stakeholders ? $request->additional_stakeholders : '';
        $item->source = $request->source;
        $item->save();

        return redirect($this->route);
    }
}
