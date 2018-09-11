@extends('layouts.app')
@section('scripts')
@append

@section('content')
    <!--
    #if($task === 'new')
    #    include('mitigations.partials.form')
    #endif
    -->
    <!-- Current Tasks -->
    @if (count($tests) > 0)
    <table class="table text-center">

        <!-- Table Headings -->
        <thead>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </thead>

        <!-- Table Body -->
        <tbody>
            @foreach ($tests as $test)
                <tr>
                    <td class="table-text text-left">
                        <a href="{{ url('tests/' . $test->id) }}">{{ $test->name }}</a>
                    </td>
                    <td class="table-text text-left">
                        {{ \Carbon\Carbon::parse($test->created_at)->format('Y-m-d') }}
                    </td>
                    <td class="table-text text-left">
                        {{ $test->last_date}}
                    </td>
                    <td class="table-text text-left">
                        {{ $test->next_dat }}
                    </td>
                    <td class="table-text text-left">
                        {{ $test->objective }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
           
    @endif
@endsection