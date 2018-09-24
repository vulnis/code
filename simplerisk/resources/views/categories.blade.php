@extends('layouts.app') 
@section('scripts') @append 
@section('content')
    @include('layouts.partials.tabs', ['items' => trans_choice('messages.Category',2),
'item' => trans_choice('messages.Category',1)])
<div class="tab-content" id="pageTab">
    <div class="tab-pane fade show active" id="page-list-tab" role="tabpanel" aria-labelledby="list-tab">
        @if (count($categories) > 0)
        <table class="table table-borderless">

            <!-- Table Headings -->
            <thead>
                <th class="text-left">@lang('messages.Name')</th>
                <th>@lang('messages.Type')</th>
                <th></th>
            </thead>

            <!-- Table Body -->
            <tbody>
                @foreach ($categories as $item)
                <tr>

                    <td class="table-text text-left">
                        <a href="{{ url('categories/' . $item->value) }}">{{ $item->name }}</a>
                    </td>
                    <td>
                        @choice('messages.' . $item->type, 1)
                    </td>
                    <td><a href="#"><i data-id="{{ $item->value}}" class="fas fa-trash-alt fa-fw"></i></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
    <div class="tab-pane" id="page-new-tab" role="tabpanel" aria-labelledby="new-tab">
    @include('forms.default',['formtitle' => trans_choice('messages.Category',1), 'formtype' => 'category'])
    </div>
</div>
@endsection