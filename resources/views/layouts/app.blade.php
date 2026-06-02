<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Бронирование') — {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('head')
</head>
<body>
    <div class="device">
        <div class="app">
            <header class="appbar">
                <div class="appbar__inner">
                    <a class="appbar__brand" href="{{ url('/') }}">
                        <i class="bi bi-mic-fill"></i>
                        <span>Конференции<b>.РФ</b></span>
                    </a>

                    @auth
                        <nav class="appbar__nav">
                            @if (auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}"
                                   class="appbar__link {{ request()->routeIs('admin.*') ? 'is-active' : '' }}">
                                    <i class="bi bi-grid-1x2-fill"></i> Заявки
                                </a>
                            @else
                                <a href="{{ route('cabinet') }}"
                                   class="appbar__link {{ request()->routeIs('cabinet') ? 'is-active' : '' }}">
                                    <i class="bi bi-house-door-fill"></i> Кабинет
                                </a>
                                <a href="{{ route('bookings.create') }}"
                                   class="appbar__link {{ request()->routeIs('bookings.*') ? 'is-active' : '' }}">
                                    <i class="bi bi-plus-circle-fill"></i> Заявка
                                </a>
                            @endif
                            <span class="appbar__user"><i class="bi bi-person-circle"></i> {{ auth()->user()->full_name }}</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="appbar__link appbar__logout"><i class="bi bi-box-arrow-right"></i> Выход</button>
                            </form>
                        </nav>
                    @endauth
                </div>
            </header>

            <main class="content">
                @yield('content')
            </main>

            @auth
                @include('partials.nav')
            @endauth
        </div>

        @include('partials.flash')
    </div>

    @stack('scripts')
    <script>
        // Авто-скрытие всплывающих уведомлений
        document.querySelectorAll('.toast').forEach(function (t) {
            setTimeout(function () {
                t.classList.add('hide');
                setTimeout(function () { t.remove(); }, 350);
            }, 3500);
        });
    </script>
</body>
</html>
