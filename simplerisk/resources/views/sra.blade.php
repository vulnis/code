@extends('layouts.app')
@section('scripts')
@append

@section('content')
<form method="POST" action="{{ url('assessment') }}" class="form">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-sm-12 col-md-4">
            <div class="simple">
                <div class="form-group">
                    <label for="sra-hazard">@lang('messages.Hazard')</label>
                    <select class="form-control" id="sra-hazard" name="hazard" @if($sra) disabled @endif>
                        @foreach ($hazards as $i => $hazard)
                            @if($hazard)
                                @if($sra)
                                    @if($sra->hazard_id === $hazard->id) 
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
                    <label for="sra-cause">@lang('messages.Cause')</label>
                    <select class="form-control" id="sra-cause" name="cause" @if($sra) disabled @endif>
                        @foreach ($causes as $i => $cause)
                            @if($cause)
                                @if($sra)
                                    @if($sra->cause_id === $cause->id) 
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
                    <label for="sra-probability">@lang('messages.Probability')</label>
                    <select class="form-control" id="sra-probability" name="probability" @if($sra) disabled @endif>
                        @foreach ($probabilities as $i => $probability)
                            @if($probability)
                                @if($sra)
                                    @if($sra->probability_id === $probability->value) 
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
                    <label for="sra-severity">@lang('messages.Severity')</label>
                    <select class="form-control" id="sra-severity" name="severity" @if($sra) disabled @endif>
                        @foreach ($severities as $i => $severity)
                            @if($severity)
                                @if($sra)
                                    @if($sra->severity_id === $severity->value) 
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
        @if(!$sra)
        <button type="submit" name="submit" class="btn btn-primary pull-right save-hazard-form">@lang('messages.Submit')</button>
        @endif
    </div>
</form>
@endsection