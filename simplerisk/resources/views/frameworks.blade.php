@extends('layouts.app')
@section('scripts')
@append

@section('content')
@include('layouts.partials.tabs', ['items' => trans_choice('messages.Framework',2), 'item' => trans_choice('messages.Framework',1)])
    <div class="tab-content" id="pageTab">
        <div class="tab-pane fade show active" id="page-list-tab" role="tabpanel" aria-labelledby="list-tab">

@if (count($frameworks) > 0)

    <table class="table table-borderless">
        <thead>
            <tr>
                <th>@choice('messages.Framework',1)</th>
                <th>@lang('messages.Description')</th>
                <th>@lang('messages.Status')</th>
                <th>@lang('messages.Parent')</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($frameworks as $item)
                <tr>
                    <td>
                        <a href="{{ url('frameworks/' . $item->value) }}">{{ $item->name }}</a>
                    </td>
                    <td>
                        @if($item->description)
                        {{ $item->description }}
                        @endif
                    </td>
                    <td>
                        @if($item->status)
                        {{ $item->status }}
                        @endif
                    </td>
                    <td>@if($item->super)
                    <a href="{{ url('frameworks/' . $item->super->value) }}">{{ $item->super->name }}</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    </div>
    <div class="tab-pane" id="page-new-tab" role="tabpanel" aria-labelledby="new-tab">
        @include('forms.default',['formtitle' => trans_choice('messages.Framework',1), 'formtype' => 'framework'])
    </div>
</div>
@endsection