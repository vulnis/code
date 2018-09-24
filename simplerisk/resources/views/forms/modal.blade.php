<div class="modal form-modal" id="mitigation-form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form onsubmit="return false;" class="form">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">{{ $formtitle }}</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="@lang('messages.Close')">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ csrf_field() }}
                    <input type="hidden" class="form-dataholder" value="-1">
                    @include('forms.' . $formtype)
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('messages.Close')</button>
                    <button type="submit" class="btn btn-primary">@lang('messages.Submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>