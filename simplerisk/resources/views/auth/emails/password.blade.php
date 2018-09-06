@extends('layouts.simple')

@section('content')
<div class="row justify-content-md-center p-5">
    Click here to reset your password: <a href="{{ $link = url('password/reset', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}"> {{ $link }} </a>
</div>
