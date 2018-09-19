@extends('layouts.app')
@section('scripts')
@append

@section('content')
@include('layouts.partials.tabs', ['items' => trans_choice('messages.Framework',2), 'item' => trans_choice('messages.Framework',1)])
    <div class="tab-content" id="pageTab">
        <div class="tab-pane fade show active" id="page-list-tab" role="tabpanel" aria-labelledby="list-tab">

@if (count($frameworks) > 0)

    <table class="table">
        <thead>
            <tr>
                <th>@choice('messages.Framework',1)</th>
                <th>@lang('messages.Description')</th>
                <th>@lang('messages.Status')</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($frameworks as $item)
            @if($item->parent > 0)
                <tr>
                    <td>
                        <a href="{{ url('frameworks/' . $item->id) }}">{{ $item->name }}</a>
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
                    <td>@if($item->parent > 0)
                    <a href="{{ url('frameworks/' . $item->parent) }}">{{ $item->parent()->first()->name }}</a>
                        @endif
                    </td>
                </tr>
            @endif
            @endforeach
        </tbody>
    </table>
    @endif
    </div>
    <div class="tab-pane" id="page-new-tab" role="tabpanel" aria-labelledby="new-tab">
    <form method="POST" action="{{ url('frameworks') }}" class="form">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-sm-12 col-md-4">
            <div class="simple">
                <div class="form-group">
                    <label for="framework-name">@lang('messages.Name')</label>
                    <input type="text" name="name" id="framework-name" class="form-control" @if($framework) value="{{$framework->name}}" disabled @endif>
                </div>
                <div class="form-group">
                    <label for="framework-description">@lang('messages.Description')</label>
                    <textarea name="description" class="form-control" id="framework-description" rows="3" @if($framework) disabled>{{$framework->description}}  @else > @endif</textarea>
                </div>
                <div class="form-group">
                    <label for="framework-parent">@lang('messages.Parent')</label>
                    <select class="form-control" id="framework-category" name="parent" @if($framework) disabled @endif>
                        @foreach ($parents as $i => $parent)
                            @if($framework)
                                @if($framework->parent === $parent->value) 
                                <option selected value="{{ $parent->value}}">{{ $parent->name }}</option>
                                @else
                                <option value="{{ $parent->value}}">{{ $parent->name }}</option>
                                @endif
                            @else
                                <option @if ($i = 0) selected @endif value="{{ $parent->value}}">{{ $parent->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @if(!$framework)
        <button type="submit" name="submit" class="btn btn-primary pull-right save-framework-form">@lang('messages.Submit')</button>
        @endif
    </div>
</form>
    </div>
</div>
@endsection