@extends('layouts.app')
@section('scripts')
@append

@section('content')
@include('layouts.partials.tabs', ['items' => trans_choice('messages.Consequence',2), 'item' => trans_choice('messages.Consequence',1)])
    <div class="tab-content" id="pageTab">
        <div class="tab-pane fade show active" id="page-list-tab" role="tabpanel" aria-labelledby="list-tab">
    @if (count($consequences) > 0)
    <table class="table text-center">
        <thead>
            <th class="text-left">@lang('messages.Name')</th>
            <th>@lang('messages.Description')</th>
        </thead>
        <tbody>
            @foreach ($consequences as $item)
                <tr>
                    
                    <td class="table-text text-left">
                        <a href="{{ url('consequences/' . $item->id) }}">{{ $item->name }}</a>
                    </td>
                    <td>
                        {{ $item->description }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    </div>
    <div class="tab-pane" id="page-new-tab" role="tabpanel" aria-labelledby="new-tab">
    <form method="POST" action="{{ url('consequences') }}" class="form">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-sm-12 col-md-4">
            <div class="simple">
                <div class="form-group">
                    <label for="consequence-name">@lang('messages.Name')</label>
                    <input type="text" name="name" id="consequence-name" class="form-control" @if($consequence) value="{{$consequence->name}}" disabled @endif>
                </div>
                <div class="form-group">
                    <label for="consequence-description">@lang('messages.Description')</label>
                    <textarea name="description" class="form-control" id="consequence-description" rows="3" @if($consequence) disabled>{{$consequence->description}}  @else > @endif</textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @if(!$consequence)
        <button type="submit" name="submit" class="btn btn-primary pull-right save-consequence-form">@lang('messages.Submit')</button>
        @endif
    </div>
</form>
    </div>
</div>
@endsection