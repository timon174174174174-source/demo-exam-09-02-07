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
                <a class="appbar__brand" href="{{ url('/') }}">
                    <i class="bi bi-mic-fill"></i>
                    <span>Конференции<b>.РФ</b></span>
                </a>
                @yield('appbar-action')
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
