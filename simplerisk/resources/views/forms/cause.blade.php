<div class="form-group">
    <label for="cause-description">@lang('messages.Description')</label>
    <textarea name="description" class="form-control" id="cause-description" rows="3" @if($cause) disabled>{{$cause->description}}  @else > @endif</textarea>
</div>
<div class="form-group">
    <label for="cause-category">@choice('messages.Category',1)</label>
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
<div class="form-group">
    <label for="cause-component">@choice('messages.Component',1)</label>
    <select class="form-control" id="cause-component" name="component" @if($cause) disabled @endif>
        @foreach ($components as $i => $component)
            @if($cause)
                @if ($component->parent)
                    @if($cause->component === $component->id) 
                        <option selected value="{{ $component->id}}">{{ $component->parent->name }} - {{ $component->name }}</option>
                    @else
                        <option value="{{ $component->id}}">{{ $component->parent->name }} - {{ $component->name }}</option>
                    @endif
                @else
                    @if($cause->component === $component->id) 
                        <option selected value="{{ $component->id}}" style="font-weight:bold">{{ $component->name }}</option>
                    @else
                        <option value="{{ $component->id}}" style="font-weight:bold">{{ $component->name }}</option>
                    @endif
                @endif
            @else
                @if ($component->parent)
                    <option @if ($i = 0) selected @endif value="{{ $component->id}}">{{ $component->parent->name }} - {{ $component->name }}</option>
                @else
                    <option @if ($i = 0) selected @endif value="{{ $component->id}}" style="font-weight:bold">{{ $component->name }}</option>
                @endif
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