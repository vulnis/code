<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <!-- Branding Image -->
    <a class="navbar-brand" href="{{ url('/') }}">
        <img src='/images/logo@2x.png' alt='SimpleRisk Logo' />
    </a>
    <!-- Collapsed Hamburger -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarMenu">
        <!-- Left Side Of Navbar -->
        <ul class="navbar-nav mr-auto">
            @if (Auth::check())
                <li {!! (Request::is('*governance/*') ? 'class="nav-item active"' : 'class="nav-item"') !!}><a class="nav-link" href="{{ url('/governance/index.php') }}">@lang('messages.Governance')</a></li>
                <li {!! (Request::is('*management/*') ? 'class="nav-item active"' : 'class="nav-item"') !!}><a class="nav-link" href="{{ url('/management/index.php') }}">@lang('messages.RiskManagement')</a></li>
                <li {!! (Request::is('*compliance/*') ? 'class="nav-item active"' : 'class="nav-item"') !!}><a class="nav-link" href="{{ url('/compliance/index.php') }}">@lang('messages.Compliance')</a></li>
                <li {!! (Request::is('*assets/*') ? 'class="nav-item active"' : 'class="nav-item"') !!}><a class="nav-link" href="{{ url('/assets/index.php') }}">@lang('messages.AssetManagement')</a></li>
                <li {!! (Request::is('*assessments/*') ? 'class="nav-item active"' : 'class="nav-item"') !!}><a class="nav-link" href="{{ url('/assessments/index.php') }}">@lang('messages.RiskAssessment')</a></li>
                <li {!! (Request::is('*reports/*') ? 'class="nav-item active"' : 'class="nav-item"') !!}>
                    <a class="nav-link" href="{{ url('/reports/index.php') }}">
                        <!--<i class="fa fa-tachometer" title="@lang('messages.Reporting')" aria-hidden="true"></i>&nbsp;-->
                        <span>@lang('messages.Reporting')</span>
                    </a>
                </li>
                
            @endif
        </ul>
        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ml-auto">
            <!-- Authentication Links -->
            @if (Auth::guest())
                <li {!! (Request::is('*login') ? 'class="nav-item active"' : 'class="nav-item"') !!}><a class="nav-link" href="{{ url('/login') }}">Login</a></li>
                <li {!! (Request::is('*register') ? 'class="nav-item active"' : 'class="nav-item"') !!}><a class="nav-link" href="{{ url('/register') }}">Register</a></li>
            @else
                <li {!! (Request::is('*admin/*') ? 'class="nav-item active"' : 'class="nav-item"') !!}><a class="nav-link" href="{{ url('/admin/index.php') }}"><i class="fa fa-cog" aria-hidden="true" title="@lang('messages.Configure')"></i></a></li>
                <li class="nav-item dropdown">
                    <a href="#" id="userDropdown" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <span class="rounded-circle bg-success p-1"><i class="fa fa-user fa-fw text-white" aria-hidden="true"></i></span> {{ Auth::user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="{{ url('/account/profile.php') }}"><i class="fa fa-btn fa-cog"></i> @lang('messages.MyProfile')</a>
                        <a class="dropdown-item" href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i> @lang('messages.Logout')</a>
                    </div>
                </li>
            @endif
        </ul>
    </div>
</nav>