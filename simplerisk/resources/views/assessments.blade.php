@extends('layouts.app')
@section('scripts')
@append

@section('content')
@include('layouts.partials.tabs', ['items' => trans_choice('messages.Assessment',2), 'item' => trans_choice('messages.Assessment',1)])
<div class="tab-content" id="pageTab">
        <div class="tab-pane fade show active" id="page-list-tab" role="tabpanel" aria-labelledby="list-tab">
@if (count($assessments) > 0)
    <table class="table">

        <!-- Table Headings -->
        <thead>
            <tr>
                <th>@lang('messages.Subject')</th>
                <th></th>
                <th>@choice('messages.Cause',1)</th>
                <th>@lang('messages.Probability')</th>
                <th>@lang('messages.Severity')</th>
                <th>@lang('messages.Score')</th>
                <th></th>
            </tr>
        </thead>

        <!-- Table Body -->
        <tbody>
            @foreach ($assessments as $item)
                <tr>
                    
                    <td class="table-text text-left" style="border-left: 4px solid {{$item->getColorAttribute()}};">
                        <a href="{{ url('assessments/' . $item->id) }}">{{ $item->risk->subject }}</a>
                    </td>
                    <td>{{ $item->sub_id }}</td>
                    <td class="table-text text-left">
                    {{ $item->cause->description }}
                    </td>
                    <td>{{ $item->probability->name }}</td>
                    <td>{{ $item->severity->name }}</td>
                    <td>
                        <span class="p-2"  title="{{ $item->getLevelAttribute() }}" style="background-color:{{$item->getColorAttribute()}};">{{ $item->getScoreAttribute() }}</span>
                    </td>
                    @if($item->mitigations->count() == 0)
                    <td><a href="#"><i class="fas fa-plus fa-fw"></i></a></td>
                    @else
                    <td><a class="collapse-switch collapsed" data-toggle="collapse" href="#collapseAction{{$item->id}}" role="button" aria-expanded="false" aria-controls="collapseAction{{ $item->id}}"></a></td>
                    @endif
                    
                </tr>
                
                @if($item->mitigations->count() == 0)
                @else
                <tr>
                <td class="p-0 m-0" style="border-top:none !important;" colspan="7">
                <div class="collapse" id="collapseAction{{ $item->id}}">
                    <div class="card card-body m-2">
                        @foreach($item->mitigations as $mit)
                            {{$mit->type}}
                            {{$mit->current_solution}}
                        @endforeach
                    </div>
                </div>
                </td>
                </tr>
                @endif
                
            @endforeach
        </tbody>
    </table>
           
    @endif
    </div>
        <div class="tab-pane" id="page-new-tab" role="tabpanel" aria-labelledby="new-tab">
        
        <form method="POST" action="{{ url('assessments') }}" class="form">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-sm-12 col-md-4">
            <div class="simple">
                <div class="form-group">
                    <label for="assessment-risk">@choice('messages.Risk',1)</label>
                    <select class="form-control" id="assessment-risk" name="risk" @if($assessment) disabled @endif>
                        @foreach ($risks as $i => $risk)
                            @if($risk)
                                @if($assessment)
                                    @if($assessment->risk_id === $risk->id) 
                                    <option selected value="{{ $risk->id}}">{{ $risk->subject }}</option>
                                    @else
                                    <option value="{{ $risk->id}}">{{ $risk->subject }}</option>
                                    @endif
                                @else
                                <option @if ($i = 0) selected @endif value="{{ $risk->id}}">{{ $risk->subject }}</option>
                                @endif
                            @else
                                <option @if ($i = 0) selected @endif value="{{ $risk->id}}">{{ $risk->subject }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="assessment-cause">@choice('messages.Cause',1)</label>
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
                @if($assessment)
                    <div style="border-right: 4px solid @if($assessment->mitigations->count() == 0) #ff0000 @else #00ff00 @endif">
                        <span class="p-2"  title="{{ $assessment->getLevelAttribute() }}" style="background-color:{{$assessment->getColorAttribute()}};">{{ $assessment->getScoreAttribute() }}</span>
                    </div>
                    @foreach ($assessment->mitigations as $mitigation)
                        <div>{{$mitigation->submission_date}} - {{$mitigation->current_solution}}</div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        @if(!$assessment)
        <button type="submit" name="submit" class="btn btn-primary pull-right save-risk-form">@lang('messages.Submit')</button>
        @endif
    </div>
</form>
</div>
        </div>
@endsection