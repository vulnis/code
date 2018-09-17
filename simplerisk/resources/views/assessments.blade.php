@extends('layouts.app')
@section('scripts')
@append

@section('content')

@if (count($assessments) > 0)
    <table class="table">

        <!-- Table Headings -->
        <thead>
            <th>@lang('messages.Assessment')</th>
            <th>@lang('messages.Stage')</th>
            <th></th>
            <th>@lang('messages.Cause')</th>
            <th>@lang('messages.Probability')</th>
            <th>@lang('messages.Severity')</th>
            <th>@lang('messages.Score')</th>
        </thead>

        <!-- Table Body -->
        <tbody>
            @foreach ($assessments as $assessment)
                <tr>
                    
                    <td class="table-text text-left">
                        <a href="{{ url('assessments/' . $assessment->id) }}">{{ $assessment->hazard->name }}</a>
                    </td>
                    <td> {{ $assessment->hazard->stage->name }}</td>
                    <td>{{ $assessment->hazard->id }}-{{ $assessment->cause->id }}</td>
                    <td class="table-text text-left">
                    {{ $assessment->cause->description }}
                    </td>
                    <td>{{ $assessment->probability->name }}</td>
                    <td>{{ $assessment->severity->name }}</td>
                    <td>{{ $assessment->getLevel() }}</td>
                </tr>
                
            @endforeach
        </tbody>
    </table>
           
    @endif
@endsection