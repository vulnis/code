@extends('layouts.app')
@section('scripts')
@append

@section('content')
<form method="POST" action="{{ url('assessments') }}" class="form">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-sm-12 col-md-4">
            <div class="simple">
                <div class="form-group">
                    <label for="assessment-hazard">@lang('messages.Hazard')</label>
                    <select class="form-control" id="assessment-hazard" name="hazard" @if($assessment) disabled @endif>
                        @foreach ($hazards as $i => $hazard)
                            @if($hazard)
                                @if($assessment)
                                    @if($assessment->hazard_id === $hazard->id) 
                                    <option selected value="{{ $hazard->id}}">{{ $hazard->name }}</option>
                                    @else
                                    <option value="{{ $hazard->id}}">{{ $hazard->name }}</option>
                                    @endif
                                @else
                                <option @if ($i = 0) selected @endif value="{{ $hazard->id}}">{{ $hazard->name }}</option>
                                @endif
                            @else
                                <option @if ($i = 0) selected @endif value="{{ $hazard->id}}">{{ $hazard->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="assessment-cause">@lang('messages.Cause')</label>
                    <select class="form-control" id="assessment-cause" name="cause" @if($assessment) disabled @endif>
                        @foreach ($causes as $i => $cause)
                            @if($cause)
                                @if($assessment)
                                    @if($assessment->cause_id === $cause->id) 
                                    <option selected value="{{ $cause->id}}">{{ $cause->description }}</option>
                                    @else
                                    <option value="{{ $cause->id}}">{{ $cause->description }}</option>
                                    @endif
                                @else
                                <option @if ($i = 0) selected @endif value="{{ $cause->id}}">{{ $cause->description }}</option>
                                @endif
                            @else
                                <option @if ($i = 0) selected @endif value="{{ $cause->id}}">{{ $cause->description }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="assessment-probability">@lang('messages.Probability')</label>
                    <select class="form-control" id="assessment-probability" name="probability" @if($assessment) disabled @endif>
                        @foreach ($probabilities as $i => $probability)
                            @if($probability)
                                @if($assessment)
                                    @if($assessment->probability_id === $probability->value) 
                                    <option selected value="{{ $probability->value}}">{{ $probability->name }}</option>
                                    @else
                                    <option value="{{ $probability->value}}">{{ $probability->name }}</option>
                                    @endif
                                @else
                                <option @if ($i = 0) selected @endif value="{{ $probability->value}}">{{ $probability->name }}</option>
                                @endif
                            @else
                                <option @if ($i = 0) selected @endif value="{{ $probability->value}}">{{ $probability->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="assessment-severity">@lang('messages.Severity')</label>
                    <select class="form-control" id="assessment-severity" name="severity" @if($assessment) disabled @endif>
                        @foreach ($severities as $i => $severity)
                            @if($severity)
                                @if($assessment)
                                    @if($assessment->severity_id === $severity->value) 
                                    <option selected value="{{ $severity->value}}">{{ $severity->name }}</option>
                                    @else
                                    <option value="{{ $severity->value}}">{{ $severity->name }}</option>
                                    @endif
                                @else
                                <option @if ($i = 0) selected @endif value="{{ $severity->value}}">{{ $severity->name }}</option>
                                @endif
                            @else
                                <option @if ($i = 0) selected @endif value="{{ $severity->value}}">{{ $severity->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @if(!$assessment)
        <button type="submit" name="submit" class="btn btn-primary pull-right save-hazard-form">@lang('messages.Submit')</button>
        @endif
    </div>
</form>
@endsection