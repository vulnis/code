@extends('layouts.app')
@section('scripts')
@append

@section('content')
@include('layouts.partials.tabs', ['items' => trans_choice('messages.Source',2), 'item' => trans_choice('messages.Source',1)])
    <div class="tab-content" id="pageTab">
        <div class="tab-pane fade show active" id="page-list-tab" role="tabpanel" aria-labelledby="list-tab">
    @if (count($sources) > 0)
    <table class="table">
        <thead>
            <th class="text-left">@lang('messages.Name')</th>
            <th>@lang('messages.Type')</th>
        </thead>
        <tbody>
            @foreach ($sources as $item)
                <tr>
                    
                    <td class="table-text">
                        <a href="{{ url('sources/' . $item->id) }}">{{ $item->name }}</a>
                    </td>
                    <td>
                        @choice('messages.' . $item->type,1)
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    </div>
    <div class="tab-pane" id="page-new-tab" role="tabpanel" aria-labelledby="new-tab">
    <form method="POST" action="{{ url('sources') }}" class="form">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-sm-12 col-md-4">
            <div class="simple">
                <div class="form-group">
                    <label for="source-name">@lang('messages.Name')</label>
                    <input type="text" name="name" id="source-name" class="form-control" @if($source) value="{{$source->name}}" disabled @endif>
                </div>
                <div class="form-group">
                    <label for="source-type">@lang('messages.Type')</label>
                    <select class="form-control" id="source-type" name="type" @if($source) disabled @endif>
                        @foreach ($types as $i => $type)
                            @if($source)
                                @if($source->type === $type->value) 
                                <option selected value="{{ $type->value}}">{{ $type->name }}</option>
                                @else
                                <option value="{{ $type->value}}">{{ $type->name }}</option>
                                @endif
                            @else
                                <option @if ($i = 0) selected @endif value="{{ $type->value}}">{{ $type->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @if(!$source)
        <button type="submit" name="submit" class="btn btn-primary pull-right save-source-form">@lang('messages.Submit')</button>
        @endif
    </div>
</form>
    </div>
</div>
@endsection