<div class="form-group">
    <label for="asset-name">@lang('messages.Name')</label>
    <input type="text" name="name" id="asset-name" class="form-control" @if($asset) value="{{$asset->name}}" disabled @endif>
</div>
<div class="form-group">
    <label for="asset-description">@lang('messages.Description')</label>
    <textarea name="description" class="form-control" id="asset-description" rows="3" @if($asset) disabled>{{$asset->details}}  @else > @endif</textarea>
</div>
<div class="form-group">
    <label for="asset-category">@choice('messages.Category',1)</label>
    <select class="form-control" id="asset-category" name="category" @if($asset) disabled @endif>
        @foreach ($categories as $i => $category)
            @if($asset)
                @if($asset->category_id === $category->value) 
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