@extends('layouts.app')
@section('scripts')
@append

@section('content')
    <!--
    #if($task === 'new')
    #    include('risks.partials.form')
    #endif
    -->
    <!-- Current Tasks -->
    @if (count($categories) > 0)
    <table class="table text-center">

        <!-- Table Headings -->
        <thead>
            <th class="text-left">@lang('messages.Name')</th>
            <th>@lang('messages.Type')</th>
        </thead>

        <!-- Table Body -->
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    
                    <td class="table-text text-left">
                        <a href="{{ url('categories/' . $category->id) }}">{{ $category->name }}</a>
                    </td>
                    <td>
                        @lang('messages.' . $category->type)
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
           
    @endif
@endsection