@extends('layouts.app')
@section('scripts')
@append

@section('content')
@include('layouts.partials.tabs', ['items' => trans_choice('messages.Cause',2), 'item' => trans_choice('messages.Cause',1)])
<div class="tab-content" id="pageTab">
        <div class="tab-pane fade show active" id="page-list-tab" role="tabpanel" aria-labelledby="list-tab">
    @if (count($causes) > 0)
    <table class="table text-center">
        <thead>
            <th class="text-left">@lang('messages.Description')</th>
            <th>@choice('messages.Category', 1)</th>
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
        <form method="POST" action="{{ url('causes') }}" class="form">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-sm-12 col-md-4">
            <div class="simple">
                <div class="form-group">
                    <label for="cause-description">@lang('messages.Description')</label>
                    <textarea name="description" class="form-control" id="cause-description" rows="3" @if($cause) disabled>{{$cause->description}}  @else > @endif</textarea>
                </div>
                <div class="form-group">
                    <label for="cause-category">@lang('messages.Category')</label>
                    <select class="form-control" id="cause-category" name="category" @if($cause) disabled @endif>
                        @foreach ($categories as $i => $category)
                            @if($cause)
                                @if($cause->category === $category->value) 
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
                <fieldset class="form-group">
                <legend>@choice('messages.Consequence', 2)</legend>
                @foreach ($consequences as $i => $consequence)
                <div class="form-check form-check-inline">
                    <input class="form-check-input" name="consequence[]" type="checkbox" id="cause-consequence{{ $consequence->id }}" value="{{ $consequence->id }}">
                    <label class="form-check-label" for="cause-consequence{{ $consequence->id }}">{{ $consequence->name }}</label>
                </div>
                @endforeach
                </div>
            </div>
        </div>

    <div class="row">
        <div class="col-sm-12 col-md-4">
            <!--<input type='button' name='cvssSubmit' id='cvssSubmit' value='Score Using CVSS' />
            <input type='button' name='dreadSubmit' id='dreadSubmit' value='Score Using DREAD' onclick='javascript: popupdread();' />
            <input type='button' name='owaspSubmit' id='owaspSubmit' value='Score Using OWASP' onclick='javascript: popupowasp();' />-->
            @if(!$cause)
            <button type="submit" name="submit" class="btn btn-primary pull-right save-cause-form">@lang('messages.Submit')</button>
            @endif
        <!--<input class="btn pull-right" value="Reset" type="reset">-->
        </div>
    </div>
</form>
        </div>
</div>
@endsection