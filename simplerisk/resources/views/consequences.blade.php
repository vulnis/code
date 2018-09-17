@extends('layouts.app')
@section('scripts')
@append

@section('content')
    @if (count($consequences) > 0)
    <table class="table text-center">
        <thead>
            <th class="text-left">@lang('messages.Name')</th>
            <th>@lang('messages.Description')</th>
        </thead>
        <tbody>
            @foreach ($consequences as $consequence)
                <tr>
                    
                    <td class="table-text text-left">
                        <a href="{{ url('consequences/' . $consequence->id) }}">{{ $consequence->name }}</a>
                    </td>
                    <td>
                        {{ $consequence->description }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif
@endsection