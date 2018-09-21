@extends('layouts.app')
@section('scripts')
@append

@section('content')
@include('layouts.partials.tabs', ['items' => trans_choice('messages.Responsible',2), 'item' => trans_choice('messages.Responsible',1)])
    <div class="tab-content" id="pageTab">
        <div class="tab-pane fade show active" id="page-list-tab" role="tabpanel" aria-labelledby="list-tab">
    @if (count($responsibles) > 0)
    <table class="table table-borderless">
        <thead>
            <tr>
            <th class="text-left">@lang('messages.Name')</th>
            <th></th>
            <tr>
        </thead>
        <tbody>
            @foreach ($responsibles as $item)
                <tr>
                    
                    <td class="table-text">
                        <a href="{{ url('responsibles/' . $item->value) }}">{{ $item->name }}</a>
                    </td>
                    <td><a href="#"><i data-id="{{ $item->value}}" class="fas fa-trash-alt fa-fw"></i></a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    </div>
    <div class="tab-pane" id="page-new-tab" role="tabpanel" aria-labelledby="new-tab">
    <form method="POST" action="{{ url('responsibles') }}" class="form">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-sm-12 col-md-4">
            <div class="simple">
                <div class="form-group">
                    <label for="responsible-name">@lang('messages.Name')</label>
                    <input type="text" name="name" id="responsible-name" class="form-control" @if($responsible) value="{{$responsible->name}}" disabled @endif>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @if(!$responsible)
        <button type="submit" name="submit" class="btn btn-primary pull-right save-responsible-form">@lang('messages.Submit')</button>
        @endif
    </div>
</form>
    </div>
</div>
@endsection