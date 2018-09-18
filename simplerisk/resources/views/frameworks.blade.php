@extends('layouts.app')
@section('scripts')
@append

@section('content')
@if (count($frameworks) > 0)
    <table class="table">
        <thead>
            <tr>
                <th>@choice('messages.Framework',1)</th>
                <th>@lang('messages.Description')</th>
                <th>@lang('messages.Status')</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($frameworks as $framework)
            @if($framework->parent > 0)
                <tr>
                    <td>
                        <a href="{{ url('frameworks/' . $framework->id) }}">{{ $framework->name }}</a>
                    </td>
                    <td>
                        @if($framework->description)
                        {{ $framework->description }}
                        @endif
                    </td>
                    <td>
                        @if($framework->status)
                        {{ $framework->status }}
                        @endif
                    </td>
                    <td>@if($framework->parent > 0)
                    <a href="{{ url('frameworks/' . $framework->parent) }}">{{ $framework->parent()->first()->name }}</a>
                        @endif
                    </td>
                </tr>
            @endif
            @endforeach
        </tbody>
    </table>
    @endif
@endsection