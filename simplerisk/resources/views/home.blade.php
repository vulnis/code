@extends('layouts.app')

@section('content')
<div class="card-columns">
    <div class="card text-white bg-primary">
    <div class="card-header"><i class="fas fa-notes-medical fa-fw"></i> @choice('messages.Assessment',2)</div>
    <div class="card-body">
      <h5 class="card-title">{{$assessments->count()}} @choice('messages.Assessment',2)</h5>
      <p class="card-text">@lang('messages.AssessmentDescription')</p>
    </div>
  </div>
  <div class="card text-white bg-secondary">
    <div class="card-header"><i class="fab fa-hotjar fa-fw"></i> @choice('messages.Risk',2)</div>
    <div class="card-body">
      <h5 class="card-title">{{ $risks->count()}} @choice('messages.Risk',2)</h5>
      <p class="card-text">@lang('messages.RiskDescription')</p>
    </div>
  </div>
  <div class="card text-white bg-success">
    <div class="card-header"><i class="fas fa-fire-extinguisher"></i>  @choice('messages.Mitigation', 2)</div>
    <div class="card-body">
      <h5 class="card-title">{{ $mitigations->count() }} @choice('messages.Mitigation', 2)</h5>
      <p class="card-text">@lang('messages.MitigationDescription')</p>
    </div>
  </div>
</div>
@endsection
