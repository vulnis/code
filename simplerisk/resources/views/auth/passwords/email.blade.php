@extends('layouts.simple')

<!-- Main Content -->
@section('content')
<div class="row justify-content-md-center p-5">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header">@lang('messages.PasswordReset')</div>
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="email" class="col-md-4 control-label">@lang('messages.EmailAddress')</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" name="email" value="{{ old('email') }}">

                            @if ($errors->has('email'))
                                <div class="invalid-feedback">
                                    <em>@lang('messages.' .$errors->first('email'))</em>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-btn fa-envelope"></i> @lang('messages.Send')
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
