@extends('layouts.app')
@section('scripts')
@append

@section('content')
@include('layouts.partials.tabs', ['items' => trans_choice('messages.Risk',2), 'item' => trans_choice('messages.Risk',1)])
    <div class="tab-content" id="pageTab">
        <div class="tab-pane fade show active" id="page-list-tab" role="tabpanel" aria-labelledby="list-tab">
@if (count($risks) > 0)
    <table class="table">
        <thead>
            <tr>
                <th>@choice('messages.Risk',1)</th>
                <th>@lang('messages.DateSubmitted')</th>
                <th>@choice('messages.Category',1)</th>
                <th>@choice('messages.Source',1)</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($risks as $item)
                <tr>
                    <td>
                        <a href="{{ url('risks/' . $item->id) }}">{{ $item->subject }}</a>
                    </td>
                    <td>
                        {{ $item->submission_date->toDateString() }}
                    </td>
                    <td>
                        @if($item->category)
                        {{ $item->category()->first()->name }}
                        @endif
                    </td>
                    <td>@if($item->source)
                        {{ $item->source()->first()->name }}
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
        <form method="POST" action="{{ url('risks') }}" class="form">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-sm-12 col-md-4">
            <div class="simple">
                <div class="form-group">
                    <label for="risk-subject">@lang('messages.Subject')</label>
                    <input type="text" name="subject" id="risk-subject" class="form-control" @if($risk) value="{{$risk->subject}}" disabled @endif>
                </div>
                <div class="form-group">
                    <label for="risk-category">@lang('messages.Category')</label>
                    <select class="form-control" id="risk-category" name="category" @if($risk) disabled @endif>
                        @foreach ($categories as $i => $category)
                            @if($risk)
                                @if($risk->category === $category->value) 
                                <option selected value="{{ $category->value}}">{{ $category->name }}</option>
                                @else
                                <option value="{{ $category->value}}">{{ $category->name }}</option>
                                @endif
                            @else
                                <option @if ($i = 0) selected @endif value="{{ $category->value}}">{{ $category->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="risk-source">@choice('messages.Source',1)</label>
                    <select class="form-control" id="risk-source" name="source" @if($risk) disabled @endif>
                        @foreach ($sources as $i => $source)
                            @if($risk)
                                @if($risk->source === $source->value) 
                                <option selected value="{{ $source->value}}">{{ $source->name }}</option>
                                @else
                                <option value="{{ $source->value}}">{{ $source->name }}</option>
                                @endif
                            @else
                                <option @if ($i = 0) selected @endif value="{{ $source->value}}">{{ $source->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="risk-stage">@choice('messages.Stage',1)</label>
                    <select class="form-control" id="risk-category" name="stage" @if($risk) disabled @endif>
                        @foreach ($stages as $i => $stage)
                            @if($risk)
                                @if($risk->stage === $stage->value) 
                                <option selected value="{{ $stage->id}}">{{ $stage->name }}</option>
                                @else
                                <option value="{{ $stage->id}}">{{ $stage->name }}</option>
                                @endif
                            @else
                                <option @if ($i = 0) selected @endif value="{{ $stage->id}}">{{ $stage->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @if(!$risk)
        <button type="submit" name="submit" class="btn btn-primary pull-right save-risk-form">@lang('messages.Submit')</button>
        @endif
    </div>
</form>
        </div>
    </div>
@endsection