@extends('layouts.app')
@section('scripts')
@append

@section('content')
    <!--
    #if($task === 'new')
    #    include('risks.partials.form')
    #endif
    -->
    <!-- Current Tasks -->
    @if (count($risks) > 0)
    <table class="table text-center">

        <!-- Table Headings -->
        <thead>
            <th class="text-left">@lang('messages.Subject')</th>
            <th>@lang('messages.InherentRisk')</th>
            <th>@lang('messages.DateSubmitted')</th>
            <th>@lang('messages.Status')</th>
            <th>@lang('messages.MitigationPlanned')</th>
            <th>@lang('messages.ManagementReview')</th>
        </thead>

        <!-- Table Body -->
        <tbody>
            @foreach ($risks as $risk)
                <tr>
                    
                    <td class="table-text text-left">
                        <a href="{{ url('risk/' . $risk->id) }}">{{ $risk->subject }}</a>
                    </td>
                    @if($risk->score)
                    <td class="table-text" title="{{ $risk->score->getLevel()['name'] }}" style="background-color:{{ $risk->score->getLevel()['color'] }}">
                        {{ $risk->score->calculated_risk }}
                    </td>
                    @else
                    <td></td>
                    @endif

                    <td class="table-text">
                        {{ $risk->submission_date->toDateString() }}
                    </td>
                    <td class="table-text">
                    @if ($risk->status === 'New')
                        <i class="fas fa-inbox text-danger fa-fw" title="@lang('messages.New')"></i>
                    @elseif ($risk->status === 'Mitigation Planned')
                        <i class="fas fa-hourglass-start text-success fa-fw" title="@lang('messages.MitigationPlanned')"></i> 
                    @elseif ($risk->status === 'Mgmt Reviewed')
                        <i class="fas fa-thumbs-up text-success fa-fw" title="@lang('messages.ManagementReviewed')"></i> 
                    @elseif ($risk->status === 'Closed')
                        <i class="fas fa-archive text-success fa-fw" title="@lang('messages.Closed')"></i> 
                    @elseif ($risk->status === 'Reopened')
                        <i class="fas fa-thumbs-down text-danger fa-fw" title="@lang('messages.Reopened')"></i>
                    @elseif ($risk->status === 'Untreated')
                        <i class="fas fa-question-circle text-danger fa-fw" title="@lang('messages.Untreated')"></i>
                    @elseif ($risk->status === 'Treated')
                        <i class="fas fa-hourglass text-danger fa-fw" title="@lang('messages.Treated')"></i>
                    @else
                        <i class="fas fa-exclamation-triangle text-danger fa-fw" title="@lang('messages.Unassigned')"></i>
                    @endif
                    </td>
                    <td>
                    @if ($risk->mitigation_id === 0)
                        <a href="{{ url('management/mitigate.php?type=1&amp;id=' .$risk->id) }}">
                            <i class="fas fa-check text-success fa-fw" title="@lang('messages.Yes')"></i>
                        </a>
                    @else
                        <a href="{{ url('management/mitigate.php?type=1&amp;id=' .$risk->id) }}">
                            <i class="fas fa-times text-danger fa-fw" title="@lang('messages.No')"></i>
                        </a>
                    @endif
                    </td>
                    <td class="table-text">
                        @if($risk->mgmt_review  > 0 )
                            @if($risk->next_review_date)
                                @if($risk->next_review_date >= new DateTime('today'))
                                    <i class="fas fa-check text-success fa-fw" title="@lang('messages.Yes')"></i>
                                @else
                                    <i class="fas fa-times text-danger fa-fw" title="@lang('messages.No')"></i>
                                @endif
                            @else
                                <i class="fas fa-check text-success fa-fw" title="@lang('messages.Yes')"></i>
                            @endif
                        @else
                            <i class="fas fa-times text-danger fa-fw" title="@lang('messages.No')"></i>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
           
    @endif
@endsection