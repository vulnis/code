@extends('layouts.app')
@section('scripts')
@append

@section('content')
    <!--
    #if($task === 'new')
    #    include('mitigations.partials.form')
    #endif
    -->
    <!-- Current Tasks -->
    @if (count($mitigations) > 0)
    <table class="table text-center">

        <!-- Table Headings -->
        <thead>
            <th class="text-left">@lang('messages.Risk')</th>
            <th class="text-left">@lang('messages.SubmissionDate')</th>
            <th class="text-left">@lang('messages.Update')</th>
            <th class="text-left">@lang('messages.MitigationPlanning')</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </thead>

        <!-- Table Body -->
        <tbody>
            @foreach ($mitigations as $mitigation)
                <tr>
                    <td class="table-text text-left">
                        <a href="{{ url('mitigation/' . $mitigation->id) }}">{{ $mitigation->risk->subject }}</a>
                    </td>
                    <td class="table-text text-left">
                        {{ \Carbon\Carbon::parse($mitigation->submission_date)->format('Y-m-d') }}
                    </td>
                    <td class="table-text text-left">
                        @if($mitigation->last_update > $mitigation->submission_date){{ \Carbon\Carbon::parse($mitigation->submission_date)->format('Y-m-d')}} @endif
                    </td>
                    <td class="table-text text-left">
                        {{ $mitigation->planning_date }}
                    </td>
                    <td class="table-text text-left">
                        {{ $mitigation->current_solution }}
                    </td>
                    <td class="table-text text-left">
                        {{ $mitigation->security_requirements }}
                    </td>
                    <td class="table-text text-left">
                        {{ $mitigation->security_recommendations }}
                    </td>
                    <td class="table-text text-left">
                        {{ $mitigation->mitigation_controls }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
           
    @endif
@endsection