@extends('layouts.app')
@section('scripts')
@append

@section('content')
<form method="POST" action="{{ url('causes') }}" class="form">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-sm-12 col-md-4">
            <div class="simple">
                <div class="form-group">
                    <label for="cause-description">@lang('messages.Description')</label>
                    <textarea name="description" class="form-control" id="cause-description" rows="3" @if($cause) disabled>{{$cause->description}}  @else > @endif</textarea>
                </div>
                <div class="form-group">
                    <label for="cause-category">@lang('messages.Category')</label>
                    <select class="form-control" id="cause-category" name="category" @if($cause) disabled @endif>
                        @foreach ($categories as $i => $category)
                            @if($cause)
                                @if($cause->category === $category->value) 
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
                <fieldset class="form-group">
                <legend>@lang('messages.Consequence')</legend>
                @foreach ($consequences as $i => $consequence)
                <div class="form-check form-check-inline">
                    <input class="form-check-input" name="consequence[]" type="checkbox" id="cause-consequence{{ $consequence->id }}" value="{{ $consequence->id }}">
                    <label class="form-check-label" for="cause-consequence{{ $consequence->id }}">{{ $consequence->name }}</label>
                </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <!--<input type='button' name='cvssSubmit' id='cvssSubmit' value='Score Using CVSS' />
        <input type='button' name='dreadSubmit' id='dreadSubmit' value='Score Using DREAD' onclick='javascript: popupdread();' />
        <input type='button' name='owaspSubmit' id='owaspSubmit' value='Score Using OWASP' onclick='javascript: popupowasp();' />-->
        @if(!$cause)
        <button type="submit" name="submit" class="btn btn-primary pull-right save-cause-form">@lang('messages.Submit')</button>
        @endif
        <!--<input class="btn pull-right" value="Reset" type="reset">-->
    </div>
</form>
@endsection