<nav id="sidebar" class="bg-dark active">
        <ul class="list-unstyled components">
            <li>
                <a href="{{ url('risks/create') }}" role="button">
                    <i class="fas fa-fire fa-fw"></i>
                    <span>@choice('messages.Risk',1)</span>
                </a>
            </li>
            <li>
                <a href="{{ url('assessments/create') }}" role="button"><i class="fas fa-search fa-fw"></i>
                    <span>@choice('messages.Assessment',1)</span></a>
            </li>
            <li>
                <a href="{{ url('categories/create') }}" role="button"><i class="fas fa-list-alt fa-fw"></i>
                    <span>@lang('messages.Category')</span></a>
            </li>
            <li>
                <a href="{{ url('causes/create') }}" role="button"><i class="fas fa-lightbulb fa-fw"></i>
                    <span>@choice('messages.Cause',1)</span></a>
            </li>
            <li>
                <a href="{{ url('consequences/create') }}" role="button"><i class="far fa-lightbulb fa-fw"></i>
                    <span>@choice('messages.Consequence', 1)</span></a>
            </li>
            <li>
                <a href="{{ url('stages/create') }}" role="button"><i class="fas fa-chalkboard-teacher fa-fw"></i>
                    <span>@lang('messages.Stage')</span></a>
            </li>
            <li>
                <a href="{{ url('sources/create') }}" role="button"><i class="fas fa-user-ninja fa-fw"></i>
                    <span>@lang('messages.Source')</span></a>
            </li>
        </ul>
    </nav>