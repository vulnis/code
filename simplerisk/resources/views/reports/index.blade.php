@extends('layouts.app')

@section('scripts')
@foreach($charts as $chart)
    {!! $chart->script() !!}
@endforeach
@append

@section('content')
<div class="row">
    @include('layouts.partials.side')
    <div class="col-9">
        <div class="row p-5">
        @foreach($charts as $chart)
            <div class="col-3">
                <figure class="figure" style="min-width:200px;">
                    <figcaption class="figure-caption text-center">{{$chart->getTitle()}}</figcaption>
                    <div class="figure-img img-fluid rounded">
                    {!! $chart->container() !!}
                    </div>
                </figure>
            </div>
        @endforeach
        </div>
        <div class="row p-5">
            <div class="col">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th scope="col"></th>
                                @foreach($table as $key => $col)
                                    <th class="text-center" scope="col"><small>{!! str_replace('-', '</small><h6>', $key) !!}</h6></th>
                                @endforeach
                            </tr>
                        </thread>
                        <tbody>
                            <tr>
                                <th scope="row">@lang('messages.OpenedRisks')</th>
                                @foreach($table as $key => $col)
                                <td class="text-center">{{ $col['open'] }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <th scope="row">@lang('messages.ClosedRisks')</th>
                                @foreach($table as $key => $col)
                                <td class="text-center">{{ $col['closed'] }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <th scope="row">@lang('messages.RiskTrend')</th>
                                @foreach($table as $key => $col)
                                <td class="text-center">
                                @if ($col['trend'] > 0)
                                <span class="text-danger">+{{ $col['trend'] }}</span>
                                @elseif ($col['trend'] === 0)
                                <span>{{ $col['trend'] }}</span>
                                @else
                                <span class="text-success">-{{ $col['trend'] }}</span>
                                @endif
                                </td>
                                @endforeach
                            </tr>
                            <tr>
                                <th scope="row">@lang('messages.TotalOpenRisks')</th>
                                @foreach($table as $key => $col)
                                <td class="text-center font-weight-bold">{{ $col['trend'] }}</td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection