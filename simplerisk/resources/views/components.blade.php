@extends('layouts.app') 
@section('scripts') @append 
@section('content')
    @include('layouts.partials.tabs', ['items' => trans_choice('messages.Component',2),
'item' => trans_choice('messages.Component',1)])
<div class="tab-content" id="pageTab">
    <div class="tab-pane fade show active" id="page-list-tab" role="tabpanel" aria-labelledby="list-tab">
        @if (count($components) > 0)
        <table class="table table-borderless">

            <!-- Table Headings -->
            <thead>
                <tr>
                    <th>@lang('messages.Name')</th>
                    <th>@choice('messages.Category',1)</th>
                    <th>@lang('messages.Description')</th>
                    <th>@choice('messages.Parent',1)</th>
                    <th></th>
                </tr>
            </thead>

            <!-- Table Body -->
            <tbody>
                @foreach ($components as $item)
                <tr>

                    <td class="table-text text-left">
                        @if ($item->children->count() > 0) <strong> @endif
                        <a  href="{{ url('assets/' . $item->id) }}">{{ $item->name }}</a>
                        @if ($item->children->count() > 0) </strong> @endif
                    </td>
                    <td class="table-text text-left">
                        {{ $item->category->name }}
                    </td>
                    <td class="table-text text-left">
                        {{ $item->description }}
                    </td>
                    <td>
                    @if ($item->parent)
                        {{ $item->parent->name}}
                    @endif
                    </td>
                    <td><a href="#"><i data-id="{{ $item->id}}" class="fas fa-trash-alt fa-fw"></i></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @endif
    </div>
    <div class="tab-pane" id="page-new-tab" role="tabpanel" aria-labelledby="new-tab">
        @include('forms.default',['formtitle' => trans_choice('messages.Component',1), 'formtype' => 'component'])
    </div>
</div>
@endsection