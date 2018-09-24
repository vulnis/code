<div class="form-group">
    <label for="category-name">@lang('messages.Name')</label>
    <input type="text" name="name" id="category-name" class="form-control" @if($category) value="{{$category->name}}" disabled
        @endif>
</div>
<div class="form-group">
    <label for="category-type">@lang('messages.Type')</label>
    <select class="form-control" id="category-type" name="type" @if($category) disabled @endif>
    @foreach ($types as $i => $type)
        @if($category)
            @if($category->type === $type->value) 
                <option selected value="{{ $type->value}}">{{ $type->name }}</option>
            @else
                <option value="{{ $type->value}}">{{ $type->name }}</option>
            @endif
        @else
            <option @if ($i = 0) selected @endif value="{{ $type->value}}">{{ $type->name }}</option>
        @endif
    @endforeach
    </select>
</div>
