@extends('layouts.app')
@section('scripts')
@append

@section('content')
@include('layouts.partials.tabs', ['items' => trans_choice('messages.Cause',2), 'item' => trans_choice('messages.Cause',1)])
<div class="tab-content" id="pageTab">
        <div class="tab-pane fade show active" id="page-list-tab" role="tabpanel" aria-labelledby="list-tab">
    @if (count($causes) > 0)
    <table class="table table-borderless">
        <thead>
            <th class="text-left">@lang('messages.Description')</th>
            <th>@choice('messages.Category', 1)</th>
            <th>@choice('messages.Component', 1)</th>
            <th>@choice('messages.Consequence', 2)</th>
            <th></th>
        </thead>
        <tbody>
            @foreach ($causes as $item)
                <tr>
                    
                    <td class="table-text text-left">
                        <a href="{{ url('causes/' . $item->id) }}">{{ $item->description }}</a>
                    </td>
                    <td>
                        {{ $item->category->name }}
                    </td>
                    <td>
                        @if($item->component)
                            @if($item->component->parent)
                            {{ $item->component->parent->name }} - {{ $item->component->name }}
                            @else
                                <strong>{{ $item->component->name }}</strong>
                            @endif
                        @endif
                    </td>
                    <td>
                        @foreach ($item->consequences as $consequence)
                        <a href="{{ url('consequences/' . $consequence->id) }}" class="badge badge-primary p-2">{{$consequence->name}}</a>&nbsp;
                        @endforeach
                    </td>
                    <td><a href="#"><i data-id="{{ $item->id}}" class="fas fa-trash-alt fa-fw"></i></a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    </div>
    <div class="tab-pane" id="page-new-tab" role="tabpanel" aria-labelledby="new-tab">
        @include('forms.default',['formtitle' => trans_choice('messages.Cause',1), 'formtype' => 'cause'])
    </div>
</div>
@endsection