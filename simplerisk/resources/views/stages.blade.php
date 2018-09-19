@extends('layouts.app')
@section('scripts')
@append

@section('content')
@include('layouts.partials.tabs', ['items' => trans_choice('messages.Stage',2), 'item' => trans_choice('messages.Stage',1)])
    <div class="tab-content" id="pageTab">
        <div class="tab-pane fade show active" id="page-list-tab" role="tabpanel" aria-labelledby="list-tab">
    @if (count($stages) > 0)
    <table class="table table-borderless">
        <thead>
            <th class="text-left">@lang('messages.Name')</th>
            <th>@lang('messages.Description')</th>
            <th></th>
        </thead>
        <tbody>
            @foreach ($stages as $item)
                <tr>
                    
                    <td class="table-text text-left">
                        <a href="{{ url('stages/' . $item->id) }}">{{ $item->name }}</a>
                    </td>
                    <td>
                        {{ $item->description }}
                    </td>
                    <td><a href="#"><i data-id="{{ $item->id}}" class="fas fa-trash-alt fa-fw"></i></a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    </div>
    <div class="tab-pane" id="page-new-tab" role="tabpanel" aria-labelledby="new-tab">
    <form method="POST" action="{{ url('stages') }}" class="form">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-sm-12 col-md-4">
            <div class="simple">
                <div class="form-group">
                    <label for="stage-name">@lang('messages.Name')</label>
                    <input type="text" name="name" id="stage-name" class="form-control" @if($stage) value="{{$stage->name}}" disabled @endif>
                </div>
                <div class="form-group">
                    <label for="stage-description">@lang('messages.Description')</label>
                    <textarea name="description" class="form-control" id="stage-description" rows="3" @if($stage) disabled>{{$stage->description}}  @else > @endif</textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @if(!$stage)
        <button type="submit" name="submit" class="btn btn-primary pull-right save-stage-form">@lang('messages.Submit')</button>
        @endif
    </div>
</form>
    </div>
    </div>
@endsection