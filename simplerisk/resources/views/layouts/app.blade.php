<!DOCTYPE html>
<html lang="en">
@include('layouts.partials.head')
<body id="app-layout">
    @include('layouts.partials.navigation')
    @include('common.errors')
    <main role="main">
    <div class="container-fluid bg-white">
        @yield('content')
    </div>
    </main>
     @include('layouts.partials.footer')
     @yield('scripts')
</body>
</html>
