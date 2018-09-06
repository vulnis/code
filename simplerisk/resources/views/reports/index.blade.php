@extends('layouts.app')
@section('content')
<div class="row">
    @include('layouts.partials.side')
    <div class="col-9">
        <div class="row">
            @foreach($collections as $collection)
                @foreach ($collection as $item)
                <div class="col-sm-6 col-md-4 col-lg-2 p-2">
                    <div class="card p-2 text-center">
                        <div class="card-body">
                            @if(isset($item->color))
                                <h5 class="card-title">
                                    <span style="color:{{ $item->color }};">{{ $item->risks }}</span>
                                </h5>
                            @else
                                <h5 class="card-title">{{ $item->risks }}</h5>
                            @endif
                            <small class="card-text text-muted"><b>{{ $item->name }}</b></small>
                        </div>
                    </div>
                </div>
                @endforeach
            @endforeach
            <div class="col-auto"></div>
        </div>
    </div>
</div>
@endsection