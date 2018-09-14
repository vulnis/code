@extends('layouts.app')
@section('scripts')
@append

@section('content')
<form method="POST" action="{{ url('hazard') }}" class="form">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-sm-12 col-md-4">
            <div class="simple">
                <div class="form-group">
                    <label for="hazard-name">@lang('messages.Name')</label>
                    <input type="text" name="name" id="hazard-name" class="form-control" @if($hazard) value="{{$hazard->name}}" disabled @endif>
                </div>
                <div class="form-group">
                    <label for="hazard-description">@lang('messages.Description')</label>
                    <textarea name="description" class="form-control" id="hazard-description" rows="3" @if($hazard) disabled>{{$hazard->description}}  @else > @endif</textarea>
                </div>
                <div class="form-group">
                    <label for="hazard-category">@lang('messages.Category')</label>
                    <select class="form-control" id="hazard-category" name="category" @if($hazard) disabled @endif>
                        @foreach ($categories as $i => $category)
                            @if($hazard)
                                @if($hazard->category === $category->value) 
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
                    <label for="hazard-source">@lang('messages.Source')</label>
                    <select class="form-control" id="hazard-source" name="source" @if($hazard) disabled @endif>
                        @foreach ($sources as $i => $source)
                            @if($hazard)
                                @if($hazard->source === $source->value) 
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
                    <label for="hazard-stage">@lang('messages.Stage')</label>
                    <select class="form-control" id="hazard-category" name="stage" @if($hazard) disabled @endif>
                        @foreach ($stages as $i => $stage)
                            @if($hazard)
                                @if($hazard->stage === $stage->value) 
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
        @if(!$hazard)
        <button type="submit" name="submit" class="btn btn-primary pull-right save-hazard-form">@lang('messages.Submit')</button>
        @endif
    </div>
</form>
@endsection