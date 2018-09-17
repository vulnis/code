<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

        <!-- Fonts -->
        
        <link href="{{ mix('css/simplerisk.css') }}" rel="stylesheet">
        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }
            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">@lang('welcome.Home')</a>
                    @else
                        <a href="{{ route('login') }}">@lang('welcome.Login')</a>
                        <a href="{{ route('register') }}">@lang('welcome.Register')</a>
                    @endauth
                </div>
            @endif
            <div class="content">
                <div class="title">
                    {{ config('app.name') }}
                </div>
                <div class="subtitle m-b-md">
                    <small class="text-muted">{{ config('app.version') }}</small>
                </div>

                <div class="links">
                    <a href="https://simplerisk.freshdesk.com/support/solutions">@lang('welcome.Documentation')</a>
                    <a href="https://simplerisk.freshdesk.com/support/solutions/folders/6000228831">@lang('welcome.Tutorials')</a>
                    <a href="https://github.com/simplerisk/code">@lang('welcome.Github')</a>
                </div>
            </div>
        </div>
    </body>
</html>
