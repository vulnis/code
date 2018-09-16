@extends('layouts.app')
@section('scripts')
@append

@section('content')
    @if (count($stages) > 0)
    <table class="table text-center">
        <thead>
            <th class="text-left">@lang('messages.Name')</th>
            <th>@lang('messages.Description')</th>
        </thead>
        <tbody>
            @foreach ($stages as $stage)
                <tr>
                    
                    <td class="table-text text-left">
                        <a href="{{ url('stages/' . $stage->id) }}">{{ $stage->name }}</a>
                    </td>
                    <td>
                        {{ $stage->description }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif
@endsection