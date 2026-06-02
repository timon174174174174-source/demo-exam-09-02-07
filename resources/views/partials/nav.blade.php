<nav class="tabbar">
    @if (auth()->user()->isAdmin())
        <a href="{{ route('admin.dashboard') }}"
           class="tabbar__item {{ request()->routeIs('admin.*') ? 'is-active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i><span>Заявки</span>
        </a>
    @else
        <a href="{{ route('cabinet') }}"
           class="tabbar__item {{ request()->routeIs('cabinet') ? 'is-active' : '' }}">
            <i class="bi bi-house-door-fill"></i><span>Кабинет</span>
        </a>
        <a href="{{ route('bookings.create') }}"
           class="tabbar__item {{ request()->routeIs('bookings.*') ? 'is-active' : '' }}">
            <i class="bi bi-plus-circle-fill"></i><span>Заявка</span>
        </a>
    @endif

    <form method="POST" action="{{ route('logout') }}" class="tabbar__logout">
        @csrf
        <button type="submit"><i class="bi bi-box-arrow-right"></i><span>Выход</span></button>
    </form>
</nav>
