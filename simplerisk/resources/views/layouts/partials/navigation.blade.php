<header><nav class="navbar navbar-expand-lg navbar-dark bg-dark">
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
                <li {!! (Request::is('*risks*') ? 'class="nav-item active"' : 'class="nav-item"') !!}><a class="nav-link" href="{{ url('/risks') }}">@choice('messages.Risk',2)</a></li>
                <li {!! (Request::is('*causes*') ? 'class="nav-item active"' : 'class="nav-item"') !!}><a class="nav-link" href="{{ url('/causes') }}">@choice('messages.Cause',2)</a></li>
                <li {!! (Request::is('*assessments*') ? 'class="nav-item active"' : 'class="nav-item"') !!}><a class="nav-link" href="{{ url('/assessments') }}">@choice('messages.Assessment',2)</a></li>
                <li class="nav-item dropdown">
                    <a href="#" id="addDropdown" class="nav-link dropdown-toggle bg-primary rounded" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <span class=""><i class="fa fa-plus fa-fw text-white" aria-hidden="true"></i></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="addDropdown">
                        <a class="dropdown-item" href="{{ url('risks/create') }}" role="button"><i class="fas fa-fire fa-fw"></i> @choice('messages.Risk',1)</a>
                        <a class="dropdown-item" href="{{ url('assessments/create') }}" role="button"><i class="fas fa-search fa-fw"></i> @choice('messages.Assessment',1)</a>
                        <a class="dropdown-item" href="{{ url('categories/create') }}" role="button"><i class="fas fa-list-alt fa-fw"></i> @lang('messages.Category')</a>
                        <a class="dropdown-item" href="{{ url('causes/create') }}" role="button"><i class="fas fa-lightbulb fa-fw"></i> @choice('messages.Cause',1)</a>
                        <a class="dropdown-item" href="{{ url('consequences/create') }}" role="button"><i class="far fa-lightbulb fa-fw"></i> @choice('messages.Consequence', 1)</a>
                        <a class="dropdown-item" href="{{ url('stages/create') }}" role="button"><i class="fas fa-chalkboard-teacher fa-fw"></i> @lang('messages.Stage')</a>
                        <a class="dropdown-item" href="{{ url('sources/create') }}" role="button"><i class="fas fa-user-ninja fa-fw"></i> @lang('messages.Source')</a>
                    </div>
                </li>
                
            @endif
        </ul>
        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ml-auto">
            <!-- Authentication Links -->
            @guest
                <li {!! (Request::is('*login') ? 'class="nav-item active"' : 'class="nav-item"') !!}><a class="nav-link" href="{{ url('/login') }}">Login</a></li>
                <li {!! (Request::is('*register') ? 'class="nav-item active"' : 'class="nav-item"') !!}><a class="nav-link" href="{{ url('/register') }}">Register</a></li>
            @else
                <li {!! (Request::is('*admin/*') ? 'class="nav-item active"' : 'class="nav-item"') !!}><a class="nav-link" href="{{ url('/admin/index.php') }}"><i class="fa fa-cog" aria-hidden="true" title="@lang('messages.Configure')"></i></a></li>
                <li class="nav-item dropdown">
                    <a href="#" id="userDropdown" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <span class="rounded-circle bg-success p-1"><i class="fa fa-user fa-fw text-white" aria-hidden="true"></i></span> {{ Auth::user()->name }}
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
                                    