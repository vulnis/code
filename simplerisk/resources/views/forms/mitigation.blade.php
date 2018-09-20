<div class="modal form-modal" id="mitigation-form" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form onsubmit="return false;" class="form">
            <input type="hidden" class="form-dataholder" value="-1">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">@choice('messages.Mitigation',1)</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="@lang('messages.Close')">
                <span aria-hidden="true">&times;</span>
              </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <legend class="h5">@lang('messages.MitigationType')</legend>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" id="mitigation-type1" value="CA" checked>
                            <label class="form-check-label" for="mitigation-type1">
                                @lang('messages.CA')
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="type" id="mitigation-type2" value="PA">
                            <label class="form-check-label" for="mitigation-type2">
                                @lang('messages.PA')
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <legend class="h5">@lang('messages.MitigationReporting')</legend>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="authorities"id="mitigation-authorities" value="1">
                            <label class="form-check-label" for="mitigation-authorities">@lang('messages.ReportToAuthorities')</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="report" id="mitigation-report" value="1">
                            <label class="form-check-label" for="mitigation-report">@lang('messages.ShowInReport')</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">@lang('messages.Description')</label>
                        <textarea class="form-control" id="mitigation-description" name="description"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="assessment-risk">@choice('messages.Responsible',1)</label>
                        <select class="form-control" id="mitigation-responsible" name="responsible">
                            <option selected value="0">@lang('messages.None')</option>
                            @foreach ($responsibles as $i => $responsible)
                                @if($responsible)
                                    <option @if ($i = 0) selected @endif value="{{ $responsible->value}}">{{ $responsible->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('messages.Close')</button>
                    <button type="submit" class="btn btn-primary">@lang('messages.Submit')</button>
                </div>
            </div>
        </form>
    </div>
</div>