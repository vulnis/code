@extends('layouts.app')
@section('scripts')
@append

@section('content')
<form method="POST" action="{{ url('categories') }}" class="form">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-sm-12 col-md-4">
            <div class="simple">
                <div class="form-group">
                    <label for="category-name">@lang('messages.Name')</label>
                    <input type="text" name="name" id="category-name" class="form-control" @if($category) value="{{$category->name}}" disabled @endif>
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
        <button type="submit" name="submit" class="btn btn-primary pull-right save-category-form">@lang('messages.Submit')</button>
        @endif
    </div>
</form>
@endsection