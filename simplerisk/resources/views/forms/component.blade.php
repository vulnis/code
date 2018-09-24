<div class="form-group">
    <label for="component-name">@lang('messages.Name')</label>
    <input type="text" name="name" id="component-name" class="form-control" @if($component) value="{{$component->name}}" disabled @endif>
</div>
<div class="form-group">
    <label for="component-description">@lang('messages.Description')</label>
    <textarea name="description" class="form-control" id="component-description" rows="3" @if($component) disabled>{{$component->details}}  @else > @endif</textarea>
</div>
<div class="form-group">
    <label for="component-category">@choice('messages.Category',1)</label>
    <select class="form-control" id="component-category" name="category" @if($component) disabled @endif>
        @foreach ($categories as $i => $category)
            @if($component)
                @if($component->category_id === $category->value) 
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
    <label for="component-parent">@lang('messages.Parent')</label>
    <select class="form-control" id="component-parent" name="parent" @if($component) disabled @endif>
        @foreach ($parents as $i => $parent)
            @if($component)
                @if($component->parent_id === $parent->id) 
                <option selected value="{{ $parent->id}}">{{ $parent->name }}</option>
                @else
                <option value="{{ $parent->id}}">{{ $parent->name }}</option>
                @endif
            @else
                <option @if ($i = 0) selected @endif value="{{ $parent->id}}">{{ $parent->name }}</option>
            @endif
        @endforeach
    </select>
</div>