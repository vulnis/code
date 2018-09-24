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