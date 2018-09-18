@extends('layouts.app')
@section('scripts')
@append

@section('content')
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
            </tr>
        </thead>

        <!-- Table Body -->
        <tbody>
            @foreach ($assessments as $assessment)
                <tr>
                    
                    <td class="table-text text-left" title="{{ $assessment->getLevelAttribute() }}" style="border-left: 4px solid {{$assessment->getColorAttribute()}};">
                        <a href="{{ url('assessments/' . $assessment->id) }}">{{ $assessment->risk->subject }}</a>
                    </td>
                    <td>{{ $assessment->sub_id }}</td>
                    <td class="table-text text-left">
                    {{ $assessment->cause->description }}
                    </td>
                    <td>{{ $assessment->probability->name }}</td>
                    <td>{{ $assessment->severity->name }} {{$assessment->mitigations->count()}}</td>
                    <td title="@if ($assessment->mitigations->count() == 0)@lang('messages.None')@else{{ $assessment->mitigations->count() }} @choice('messages.Mitigation',$assessment->mitigations->count())@endif" style="border-right: 4px solid @if($assessment->mitigations->count() == 0) #ff0000 @else #00ff00 @endif">
                        <span class="p-2" style="background-color:{{$assessment->getColorAttribute()}};">{{ $assessment->getScoreAttribute() }}</span></td>
                </tr>
                
            @endforeach
        </tbody>
    </table>
           
    @endif
@endsection