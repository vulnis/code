@extends('layouts.app')
@section('content')
<div class="row">
    <@include('layouts.partials.side')
    <div class="col-9">
        <div class="row p-3 mb-2 bg-light">
            <form name="no_message" method="post">
                <h4>@lang('messages.RegisterSimpleRisk')</h4>
                <p>@lang('messages.RegistrationText')</p>
                @if ($registration_notice === true)
                <input class="btn btn-primary btn-block" type="submit" name="disable_registration_notice" value="@lang('messages.DisableRegistrationNotice')" />
                @endif
            </form>
        </div>
        <div class="row p-3 mb-2 bg-light">
            <font size="3"><b>Instance ID:</b>&nbsp;{{ $instance_id }}</font>
        </div>
          
        @unless($mysqldump === true)
        <div class="row p-3 mb-2 bg-light">
            <form method="post">
                <h4>Set Mysql Service Path</h4>
                <div class="form-group">
                    <label for="mysqldump_path">MySql Dump Path</label>
                    <input class="form-control" id="mysqldump_path" name="mysqldump_path" placeholder="{{ $mysqldumppath }}" type="text" />
                </div>
                <input class="btn btn-primary btn-block" value="@lang('messages.Submit')" name="submit_mysqlpath" type="submit" />
            </form>
        </div>
        @endunless
            
        <div class="row p-3 mb-2 bg-light">
            <h4>@lang('messages.RegistrationInformation')</h4>
            @if ($registered === true)
            <form name="register" method="post">
                @if ($update)
                <!-- Display the editable registration table -->
                <table border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>@lang('messages.FullName')&nbsp;</td>
                        <td>
                            <input type="text" name="name" id="name" value="{{ $name }}" />
                        </td>
                    </tr>
                    <tr>
                        <td>@lang('messages.Company')&nbsp;</td>
                        <td>
                            <input type="text" name="company" id="company" value="{{ $company }}" />
                        </td>
                    </tr>
                    <tr>
                        <td>@lang('messages.JobTitle')&nbsp;</td>
                        <td>
                            <input type="text" name="title" id="title" value="{{ $title }}" />
                        </td>
                    </tr>
                    <tr>
                        <td>$lang('messages.Phone')&nbsp;</td>
                        <td>
                            <input type="tel" name="phone" id="phone" value="{{ $phone }}" />
                        </td>
                    </tr>
                    <tr>
                        <td>@lang('messages.EmailAddress')&nbsp;</td>
                        <td>
                            <input type="email" name="email" id="email" value="{{ $email }}" />
                        </td>
                    </tr>
                </table>
                @endif
                <div class="form-actions">
                    <button type="submit" name="register" class="btn btn-danger">$lang('Register'])</button>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection