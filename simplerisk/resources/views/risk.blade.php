@extends('layouts.app')
@section('scripts')
@append

@section('content')
<form method="POST" action="{{ url('risks') }}" class="form">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-sm-12 col-md-4">
            <div class="simple">
                <div class="form-group">
                    <label for="risk-subject">@lang('messages.Subject')</label>
                    <input type="text" name="subject" id="risk-subject" class="form-control" @if($risk) value="{{$risk->subject}}" disabled @endif>
                </div>
                <div class="form-group">
                    <label for="risk-category">@lang('messages.Category')</label>
                    <select class="form-control" id="risk-category" name="category" @if($risk) disabled @endif>
                        @foreach ($categories as $i => $category)
                            @if($risk)
                                @if($risk->category === $category->value) 
                                <option selected value="{{ $category->value}}">{{ $category->name }}</option>
                                @else
                                <option value="{{ $category->value}}">{{ $category->name }}</option>
                                @endif
                            @else
                                <option @if ($i = 0) selected @endif value="{{ $category->value}}">{{ $category->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="risk-source">@lang('messages.Source')</label>
                    <select class="form-control" id="risk-source" name="source" @if($risk) disabled @endif>
                        @foreach ($sources as $i => $source)
                            @if($risk)
                                @if($risk->source === $source->value) 
                                <option selected value="{{ $source->value}}">{{ $source->name }}</option>
                                @else
                                <option value="{{ $source->value}}">{{ $source->name }}</option>
                                @endif
                            @else
                                <option @if ($i = 0) selected @endif value="{{ $source->value}}">{{ $source->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="risk-stage">@lang('messages.Stage')</label>
                    <select class="form-control" id="risk-category" name="stage" @if($risk) disabled @endif>
                        @foreach ($stages as $i => $stage)
                            @if($risk)
                                @if($risk->stage === $stage->value) 
                                <option selected value="{{ $stage->id}}">{{ $stage->name }}</option>
                                @else
                                <option value="{{ $stage->id}}">{{ $stage->name }}</option>
                                @endif
                            @else
                                <option @if ($i = 0) selected @endif value="{{ $stage->id}}">{{ $stage->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @if(!$risk)
        <button type="submit" name="submit" class="btn btn-primary pull-right save-risk-form">@lang('messages.Submit')</button>
        @endif
    </div>
</form>
@endsection