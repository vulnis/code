<header><nav class="navbar navbar-expand-lg navbar-dark bg-dark">
@if (Auth::check())
    <button id="sidebarCollapse" class="navbar-toggler d-block" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    @endif
    <!-- Branding Image -->
    <a class="navbar-brand pl-3" href="{{ url('/') }}">
        <img src='/images/logo@2x.png' alt='SimpleRisk Logo' />
    </a>
    <!-- Collapsed Hamburger -->
    
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarMenu">
        <!-- Left Side Of Navbar -->
        <ul class="navbar-nav mr-auto">
        <li></li>
        </ul>
        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ml-auto">
            <!-- Authentication Links -->
            @guest
                <li {!! (Request::is('*login') ? 'class="nav-item active"' : 'class="nav-item"') !!}><a class="nav-link" href="{{ url('/login') }}">Login</a></li>
                <li {!! (Request::is('*register') ? 'class="nav-item active"' : 'class="nav-item"') !!}><a class="nav-link" href="{{ url('/register') }}">Register</a></li>
            @else
                <li {!! (Request::is('*admin/*') ? 'class="nav-item active"' : 'class="nav-item"') !!}><a class="nav-link" href="{{ url('/admin/index.php') }}"><i class="fa fa-cog" aria-hidden="true" title="@lang('messages.Configure')"></i></a></li>
                <li class="nav-item">
                    <a href="#" id="userDropdown" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <span class="rounded-circle bg-success p-1"><i class="fa fa-user fa-fw text-white" aria-hidden="true"></i></span> <span class="pr-3">{{ Auth::user()->name }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="{{ url('/account/profile.php') }}"><i class="fa fa-btn fa-cog fa-fw"></i> @lang('messages.MyProfile')</a>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();"><i class="fas fa-btn fa-sign-out-alt fa-fw"></i> @lang('messages.Logout')</a>
                    </div>
                </li>
            @endguest
        </ul>
    </div>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</nav></header>
                                    