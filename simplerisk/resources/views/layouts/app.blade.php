<!DOCTYPE html>
<html lang="en">
@include('layouts.partials.head')
<body id="app-layout">
    @include('layouts.partials.navigation')
    @include('common.errors')
    <div class="container-fluid bg-white">
        @yield('content')
    </div>
     @include('layouts.partials.footer')
     @yield('scripts')
</body>
</html>
