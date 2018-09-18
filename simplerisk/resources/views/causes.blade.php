@extends('layouts.app')
@section('scripts')
@append

@section('content')
    @if (count($causes) > 0)
    <table class="table text-center">
        <thead>
            <th class="text-left">@lang('messages.Description')</th>
            <th>@lang('messages.Category')</th>
            <th>@choice('messages.Consequence', 2)</th>
        </thead>
        <tbody>
            @foreach ($causes as $cause)
                <tr>
                    
                    <td class="table-text text-left">
                        <a href="{{ url('causes/' . $cause->id) }}">{{ $cause->description }}</a>
                    </td>
                    <td>
                        {{ $cause->category->name }}
                    </td>
                    <td>
                        @foreach ($cause->consequences as $consequence)
                        <a href="{{ url('consequences/' . $consequence->id) }}" class="badge badge-primary p-2">{{$consequence->name}}</a>&nbsp;
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif
@endsection