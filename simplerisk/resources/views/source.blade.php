@extends('layouts.app')
@section('scripts')
@append

@section('content')
<form method="POST" action="{{ url('source') }}" class="form">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-sm-12 col-md-4">
            <div class="simple">
                <div class="form-group">
                    <label for="source-name">@lang('messages.Name')</label>
                    <input type="text" name="name" id="source-name" class="form-control" @if($source) value="{{$source->name}}" disabled @endif>
                </div>
                <div class="form-group">
                    <label for="source-type">@lang('messages.Type')</label>
                    <select class="form-control" id="source-type" name="type" @if($source) disabled @endif>
                        @foreach ($types as $i => $type)
                            @if($source)
                                @if($source->type === $type->value) 
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
        @if(!$source)
        <button type="submit" name="submit" class="btn btn-primary pull-right save-source-form">@lang('messages.Submit')</button>
        @endif
    </div>
</form>
@endsection