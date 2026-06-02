@extends('layouts.app')

@section('title', 'Вход')

@section('content')
    <div class="narrow">
    <div class="auth__hero">
        <h1 class="page-title">С возвращением 👋</h1>
        <p class="page-subtitle">Войдите, чтобы бронировать помещения для конференций.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="card" novalidate>
        @csrf

        <div class="field {{ $errors->has('login') ? 'field--invalid' : '' }}">
            <label class="field__label" for="login">Логин</label>
            <input class="field__control" type="text" id="login" name="login"
                   value="{{ old('login') }}" autocomplete="username" autofocus
                   placeholder="например, user123">
            @error('login')
                <div class="field__error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
            @enderror
        </div>

        <div class="field {{ $errors->has('password') ? 'field--invalid' : '' }}">
            <label class="field__label" for="password">Пароль</label>
            <input class="field__control" type="password" id="password" name="password"
                   autocomplete="current-password" placeholder="••••••••">
            @error('password')
                <div class="field__error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
            @enderror
        </div>

        <button class="btn" type="submit"><i class="bi bi-box-arrow-in-right"></i> Войти</button>
    </form>

    <p class="auth__switch">
        Ещё не зарегистрированы?
        <a href="{{ route('register') }}">Регистрация</a>
    </p>

    <div class="auth__demo">
        Демо-доступ: пользователь <b>user123 / password123</b><br>
        администратор <b>Admin26 / Demo20</b>
    </div>
    </div>
@endsection
