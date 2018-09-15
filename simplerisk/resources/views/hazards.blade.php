@extends('layouts.app')
@section('scripts')
@append

@section('content')
@if (count($hazards) > 0)
    <table class="table text-center">

        <!-- Table Headings -->
        <thead>
            <th class="text-left">@lang('messages.Hazard')</th>
            <th>@lang('messages.DateSubmitted')</th>
            <th>@lang('messages.Description')</th>
            <th>@lang('messages.Category')</th>
            <th>@lang('messages.Source')</th>
            <th>@lang('messages.Stage')</th>
        </thead>

        <!-- Table Body -->
        <tbody>
            @foreach ($hazards as $hazard)
                <tr>
                    
                    <td class="table-text text-left">
                        <a href="{{ url('hazard/' . $hazard->id) }}">{{ $hazard->name }}</a>
                    </td>
                    <td class="table-text">
                        {{ $hazard->created_at->toDateString() }}
                    </td>
                    <td class="table-text text-left">
                        {{ $hazard->description }}
                    </td>
                    <td>{{ $hazard->category->name }}</td>
                    <td>{{ $hazard->source->name }}</td>
                    <td>{{ $hazard->stage->name }}</td>
                </tr>
                
            @endforeach
        </tbody>
    </table>
           
    @endif
@endsection