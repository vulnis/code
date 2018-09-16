@extends('layouts.app')
@section('scripts')
@append

@section('content')
    @if (count($sources) > 0)
    <table class="table text-center">
        <thead>
            <th class="text-left">@lang('messages.Name')</th>
            <th>@lang('messages.Type')</th>
        </thead>
        <tbody>
            @foreach ($sources as $source)
                <tr>
                    
                    <td class="table-text text-left">
                        <a href="{{ url('sources/' . $source->id) }}">{{ $source->name }}</a>
                    </td>
                    <td>
                        @lang('messages.' . $source->type)
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif
@endsection