<div class="col-3">
    <div class="row p-3 mr-3">
        <div class="col">
            <ul class="nav nav-pills flex-column">
                @foreach ($menu as $menuitem)
                <li class="nav-item nav-item-side">
                    <a {!! (Request::path() === ($prefix. '/' . $menuitem[1]) ? 'class="nav-link nav-link-side active"' : 'class="nav-link nav-link-side"') !!} href="{{ $menuitem[1] }}">{{ $menuitem[0] }}</a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>