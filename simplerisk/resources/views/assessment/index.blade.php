@extends('layouts.app')
@section('scripts')
    @if(isset($questions))
    <script>
    var selection = [];
    var comments = [];
    $(document).ready(function(){
        setTimeout(function(){
            $(".alert").hide();
        }, 5000);
        $('.question').click(function(){
            $(this).toggleClass("bg-success text-white");
            if($(this).hasClass("bg-success")){
                console.log("push");
                selection.push(parseInt($(this).attr("data-id")));
                selection = selection.filter((v, i, a) => a.indexOf(v) === i); 
                console.log(selection);
            } else {
                console.log("pop");
                selection.pop(parseInt($(this).attr("data-id")));
                console.log(selection);
            }
        })
    })
    </script>
    @endif
@append

@section('content')
<div class="row">
    <!--@include('layouts.partials.side')-->
    <div class="col-12">
        <div class="row p-2">
            <div class="col">
                @if(isset($assessments))
                <div class="list-group">
                    @foreach($assessments as $assessment)
                    <a class="list-group-item list-group-item-action flex-column align-items-start" href="{{Request()->url}}?action=view&amp;id={{ $assessment->id}}">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1 text-primary">{{ $assessment->name }}</h5>
                            <small class="card-text text-muted">{{ $assessment->created }}</small>
                        </div>
                    </a>
                    @endforeach
                </div>
                @endif
                @if(isset($questions))
                    @if(isset($assessment))
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <h4>{{ $assessment->name }}</h4>
                            </div>
                        </div>
                        <div class="col">
                            <form action="{{ url('assessments/index.php')}}" method="get">
                            <input type='hidden' name='action' value='view' />
                            <input type='hidden' name='id' value='{{ $assessment->id }}' />
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="filter" name="q" placeholder="enter a filter" value="{{ $filter }}">
                                    <div class="input-group-append" id="button-addon4">
                                        <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                <div class="row">
                    <div class="col">
                        <div class="list-group">
                            @if(count($questions) === 0)
                                Your filter returned no results.
                            @else
                            @php ($i = 0)
                            @foreach($questions as $question)
                            <div class="question list-group-item list-group-item-action flex-column align-items-start" data-id="{{ $question->id }}">
                                <div class="row">
                                    <div class="col-1">
                                        <span class="badge badge-pill badge-light">{{ $question->id }}</span><!--<small class="text-muted">/{{ $questions->count()}}</small>-->
                                    </div>
                                    <div class="col-10">
                                        <p class="mb-1"><?=str_replace($filter, '<mark>' . $filter . '</mark>', $question->question)?></p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection