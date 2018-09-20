@extends('layouts.app') 
@section('scripts') @append 
@section('content')
    @include('layouts.partials.tabs', ['items' => trans_choice('messages.Asset',2),
'item' => trans_choice('messages.Asset',1)])
<div class="tab-content" id="pageTab">
    <div class="tab-pane fade show active" id="page-list-tab" role="tabpanel" aria-labelledby="list-tab">
        @if (count($assets) > 0)
        <table class="table table-borderless">

            <!-- Table Headings -->
            <thead>
                <tr>
                    <th>@lang('messages.Name')</th>
                    <th>@lang('messages.Description')</th>
                    <th></th>
                </tr>
            </thead>

            <!-- Table Body -->
            <tbody>
                @foreach ($assets as $item)
                <tr>

                    <td class="table-text text-left">
                        <a href="{{ url('assets/' . $item->id) }}">{{ $item->name }}</a>
                    </td>
                    <td class="table-text text-left">
                        {{ $item->details }}
                    </td>
                    <td><a href="#"><i data-id="{{ $item->id}}" class="fas fa-trash-alt fa-fw"></i></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @endif
    </div>
    <div class="tab-pane" id="page-new-tab" role="tabpanel" aria-labelledby="new-tab">

        <form method="POST" action="{{ url('assets') }}" class="form">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-sm-12 col-md-4">
                    <div class="simple">
                        <div class="form-group">
                            <label for="asset-name">@lang('messages.Name')</label>
                            <input type="text" name="name" id="asset-name" class="form-control" @if($asset) value="{{$asset->name}}" disabled @endif>
                        </div>
                        <div class="form-group">
                            <label for="asset-description">@lang('messages.Description')</label>
                            <textarea name="description" class="form-control" id="asset-description" rows="3" @if($asset) disabled>{{$asset->details}}  @else > @endif</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                @if(!$asset)
                <button type="submit" name="submit" class="btn btn-primary pull-right save-asset-form">@lang('messages.Submit')</button>                @endif
            </div>
        </form>
    </div>
</div>
@endsection