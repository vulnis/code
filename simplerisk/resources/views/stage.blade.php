@extends('layouts.app')
@section('scripts')
@append

@section('content')
<form method="POST" action="{{ url('stages') }}" class="form">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-sm-12 col-md-4">
            <div class="simple">
                <div class="form-group">
                    <label for="stage-name">@lang('messages.Name')</label>
                    <input type="text" name="name" id="stage-name" class="form-control" @if($stage) value="{{$stage->name}}" disabled @endif>
                </div>
                <div class="form-group">
                    <label for="stage-description">@lang('messages.Description')</label>
                    <textarea name="description" class="form-control" id="stage-description" rows="3" @if($stage) disabled>{{$stage->description}}  @else > @endif</textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @if(!$stage)
        <button type="submit" name="submit" class="btn btn-primary pull-right save-stage-form">@lang('messages.Submit')</button>
        @endif
    </div>
</form>
@endsection