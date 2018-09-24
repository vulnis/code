<form method="POST" action="{{ Request::path() }}" class="form">
    <div class="row">
        <div class="col-sm-12 col-md-4">
            <div class="simple">
                {{ csrf_field() }}
                @include('forms.' . $formtype)
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-4">
            <button type="submit" class="btn btn-primary">@lang('messages.Submit')</button>
        </div>
    </div>
</form>