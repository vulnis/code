@extends('layouts.app') 
@section('scripts') @append 
@section('content')
    @include('layouts.partials.tabs', ['items' => trans_choice('messages.Category',2),
'item' => trans_choice('messages.Category',1)])
<div class="tab-content" id="pageTab">
    <div class="tab-pane fade show active" id="page-list-tab" role="tabpanel" aria-labelledby="list-tab">
        @if (count($categories) > 0)
        <table class="table table-borderless">

            <!-- Table Headings -->
            <thead>
                <th class="text-left">@lang('messages.Name')</th>
                <th>@lang('messages.Type')</th>
                <th></th>
            </thead>

            <!-- Table Body -->
            <tbody>
                @foreach ($categories as $item)
                <tr>

                    <td class="table-text text-left">
                        <a href="{{ url('categories/' . $item->value) }}">{{ $item->name }}</a>
                    </td>
                    <td>
                        @choice('messages.' . $item->type, 1)
                    </td>
                    <td><a href="#"><i data-id="{{ $item->value}}" class="fas fa-trash-alt fa-fw"></i></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
    <div class="tab-pane" id="page-new-tab" role="tabpanel" aria-labelledby="new-tab">
        <form method="POST" action="{{ url('categories') }}" onsubmit="return false;" class="form">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-sm-12 col-md-4">
                    <div class="simple">
                        <div class="form-group">
                            <label for="category-name">@lang('messages.Name')</label>
                            <input type="text" name="name" id="category-name" class="form-control" @if($category) value="{{$category->name}}" disabled
                                @endif>
                        </div>
                        <div class="form-group">
                            <label for="category-type">@lang('messages.Type')</label>
                            <select class="form-control" id="category-type" name="type" @if($category) disabled @endif>
                        @foreach ($types as $i => $type)
                            @if($category)
                                @if($category->type === $type->value) 
                                <option selected value="{{ $type->value}}">{{ $type->name }}</option>
                                @else
                                <option value="{{ $type->value}}">{{ $type->name }}</option>
                                @endif
                            @else
                                <option @if ($i = 0) selected @endif value="{{ $type->value}}">{{ $type->name }}</option>
                            @endif
                        @endforeach
                    </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                @if(!$category)
                <button type="submit" name="submit" class="btn btn-primary pull-right save-category-form">@lang('messages.Submit')</button>                @endif
            </div>
        </form>
    </div>
</div>
@endsection