@extends('layouts.app')
@section('scripts')
@append

@section('content')
    @if (count($causes) > 0)
    <table class="table text-center">
        <thead>
            <th class="text-left">@lang('messages.Description')</th>
            <th>@lang('messages.Category')</th>
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
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif
@endsection