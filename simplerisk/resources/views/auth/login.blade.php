@extends('layouts.simple')

@section('content')
<div class="row justify-content-md-center p-5">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header">@lang('messages.Login')</div>
            <div class="card-body">
                <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email">@lang('messages.EmailAddress')</label>
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password">@lang('messages.Password')</label>
                        <input id="password" type="password" class="form-control" name="password">

                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>

                    <!--<div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="remember"> Remember Me
                            </label>
                        </div>
                    </div>-->

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-btn fa-sign-in-alt fa-fw"></i> @lang('messages.Login')
                        </button>

                        <a class="btn btn-link" href="{{ url('/password/reset') }}">@lang('messages.ForgotYourPassword')</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
