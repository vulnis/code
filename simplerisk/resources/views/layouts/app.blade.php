<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    

    <!-- Fonts -->
    
    <!-- Styles -->
    <link href="{{ mix('css/simplerisk.css') }}" rel="stylesheet">
</head>
<body>
    @include('layouts.partials.navigation')
    <div class="wrapper">
    @include('layouts.partials.sidebar')
    <div id="app" class="content">
        <main class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col">
                        @yield('content')
                    </div>
                </div>
            </div>
        </main>
        @include('layouts.partials.footer')
    </div>
    
</div>
</body>
</html>
