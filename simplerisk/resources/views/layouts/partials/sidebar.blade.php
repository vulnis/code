@if (Auth::check())
<nav id="sidebar" class="bg-dark active">
        
        <ul class="list-unstyled components">
            <li {!! (Request::is('*risks*') ? 'class="active"' : 'class=""') !!}>
                <a href="{{ url('risks') }}" role="button">
                    <i class="fab fa-hotjar fa-fw"></i>
                    <span>@choice('messages.Risk',1)</span>
                </a>
            </li>
            <li {!! (Request::is('*assessments*') ? 'class="active"' : 'class=""') !!}>
                <a href="{{ url('assessments') }}" role="button"><i class="fas fa-notes-medical fa-fw"></i> 
                    <span>@choice('messages.Assessment',1)</span></a>
            </li>
            <li {!! (Request::is('*categories*') ? 'class="active"' : 'class=""') !!}>
                <a href="{{ url('categories') }}" role="button"><i class="fas fa-list-alt fa-fw"></i>
                    <span>@lang('messages.Category')</span></a>
            </li>
            <li {!! (Request::is('*causes*') ? 'class="active"' : 'class=""') !!}>
                <a href="{{ url('causes') }}" role="button"><i class="fas fa-lightbulb fa-fw"></i>
                    <span>@choice('messages.Cause',1)</span></a>
            </li>
            <li {!! (Request::is('*consequences*') ? 'class="active"' : 'class=""') !!}>
                <a href="{{ url('consequences') }}" role="button"><i class="far fa-lightbulb fa-fw"></i>
                    <span>@choice('messages.Consequence', 1)</span></a>
            </li>
            <li {!! (Request::is('*stages*') ? 'class="active"' : 'class=""') !!}>
                <a href="{{ url('stages') }}" role="button"><i class="fas fa-chalkboard-teacher fa-fw"></i>
                    <span>@choice('messages.Stage',1)</span></a>
            </li>
            <li {!! (Request::is('*sources*') ? 'class="active"' : 'class=""') !!}>
                <a href="{{ url('sources') }}" role="button"><i class="fas fa-user-ninja fa-fw"></i>
                    <span>@choice('messages.Source',1)</span></a>
            </li>
            <li {!! (Request::is('*assets*') ? 'class="active"' : 'class=""') !!}>
                <a href="{{ url('assets') }}" role="button"><i class="fas fa-shopping-cart fa-fw"></i>
                    <span>@choice('messages.Asset',1)</span></a>
            </li>
            <li {!! (Request::is('*responsibles*') ? 'class="active"' : 'class=""') !!}>
                <a href="{{ url('responsibles') }}" role="button"><i class="fas fa-user-shield fa-fw"></i>
                    <span>@choice('messages.Asset',1)</span></a>
            </li>
        </ul>
        
    </nav>
    @endif