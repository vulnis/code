@extends('layouts.app')
@section('scripts')
@append

@section('content')
@if (count($risks) > 0)
    <table class="table">
        <thead>
            <tr>
                <th>@choice('messages.Risk',1)</th>
                <th>@lang('messages.DateSubmitted')</th>
                <th>@lang('messages.Category')</th>
                <th>@lang('messages.Source')</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($risks as $risk)
                <tr>
                    <td>
                        <a href="{{ url('risks/' . $risk->id) }}">{{ $risk->subject }}</a>
                    </td>
                    <td>
                        {{ $risk->submission_date->toDateString() }}
                    </td>
                    <td>
                        @if($risk->category)
                        {{ $risk->category()->first()->name }}
                        @endif
                    </td>
                    <td>@if($risk->source)
                        {{ $risk->source()->first()->name }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif
@endsection