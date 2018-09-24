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
    <label for="mitigation-description" class="col-form-label">@lang('messages.Description')</label>
    <textarea class="form-control" id="mitigation-description" name="description"></textarea>
</div>
<div class="form-group">
    <label for="mitigation-planning-date" class="col-form-label">@lang('messages.MitigationPlanning')</label>
    <input class="form-control flatpickr" id="mitigation-planning-date" name="planning_date" value="{{ Carbon\Carbon::now()->addDays(7) }}"/>
</div>
<div class="form-group">
    <label for="mitigation-reassessment" class="col-form-label">@lang('messages.Reassessment')</label>
    <select class="form-control" id="mitigation-reassessment" name="reassessment">
        <option selected value="-1">@lang('messages.Never')</option>
        <option value="7 days">@choice('messages.InWeek',1,['value' => 1])</option>
        <option value="14 days">@choice('messages.InWeek',2,['value' => 2])</option>
        <option value="4 weeks">@choice('messages.InWeek',4,['value' => 4])</option>
        <option value="1 month">@choice('messages.InMonth',1,['value' => 1])</option>
        <option value="2 months">@choice('messages.InMonth',2,['value' => 2])</option>
        <option value="1 year">@choice('messages.InYear',1,['value' => 1])</option>
    </select>
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
