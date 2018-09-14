@extends('layouts.app')
@section('scripts')
@append

@section('content')
<form method="POST" action="{{ url('consequence') }}" class="form">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-sm-12 col-md-4">
            <div class="simple">
                <div class="form-group">
                    <label for="consequence-name">@lang('messages.Name')</label>
                    <input type="text" name="name" id="consequence-name" class="form-control" @if($consequence) value="{{$consequence->name}}" disabled @endif>
                </div>
                <div class="form-group">
                    <label for="consequence-description">@lang('messages.Description')</label>
                    <textarea name="description" class="form-control" id="consequence-description" rows="3" @if($consequence) disabled>{{$consequence->description}}  @else > @endif</textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @if(!$consequence)
        <button type="submit" name="submit" class="btn btn-primary pull-right save-consequence-form">@lang('messages.Submit')</button>
        @endif
    </div>
</form>
@endsection