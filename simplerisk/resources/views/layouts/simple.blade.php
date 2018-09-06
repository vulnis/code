<!DOCTYPE html>
<html lang="en">
@include('layouts.partials.head')
<body id="app-layout" class="bg-secondary">
    @include('layouts.partials.navigation')
    <div class="container-fluid">
        @yield('content')
    </div>
     @include('layouts.partials.footer')
</body>
</html>
