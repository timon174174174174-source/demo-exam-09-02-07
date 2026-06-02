@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
    <div class="narrow">
    <div class="auth__hero">
        <h1 class="page-title">Создать аккаунт</h1>
        <p class="page-subtitle">Зарегистрируйтесь, чтобы оформлять заявки на бронирование.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="card" novalidate>
        @csrf

        <div class="field {{ $errors->has('login') ? 'field--invalid' : '' }}">
            <label class="field__label" for="login">Логин</label>
            <input class="field__control" type="text" id="login" name="login"
                   value="{{ old('login') }}" autofocus placeholder="латиница и цифры">
            @error('login')
                <div class="field__error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
            @else
                <div class="field__hint">Минимум 6 символов, только латинские буквы и цифры.</div>
            @enderror
        </div>

        <div class="field {{ $errors->has('full_name') ? 'field--invalid' : '' }}">
            <label class="field__label" for="full_name">ФИО</label>
            <input class="field__control" type="text" id="full_name" name="full_name"
                   value="{{ old('full_name') }}" placeholder="Иванов Иван Иванович">
            @error('full_name')
                <div class="field__error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
            @enderror
        </div>

        <div class="field {{ $errors->has('phone') ? 'field--invalid' : '' }}">
            <label class="field__label" for="phone">Телефон</label>
            <input class="field__control" type="tel" id="phone" name="phone"
                   value="{{ old('phone') }}" placeholder="+7 (___) ___-__-__">
            @error('phone')
                <div class="field__error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
            @enderror
        </div>

        <div class="field {{ $errors->has('email') ? 'field--invalid' : '' }}">
            <label class="field__label" for="email">E-mail</label>
            <input class="field__control" type="email" id="email" name="email"
                   value="{{ old('email') }}" placeholder="mail@example.com">
            @error('email')
                <div class="field__error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
            @enderror
        </div>

        <div class="field {{ $errors->has('password') ? 'field--invalid' : '' }}">
            <label class="field__label" for="password">Пароль</label>
            <input class="field__control" type="password" id="password" name="password"
                   placeholder="••••••••">
            @error('password')
                <div class="field__error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
            @else
                <div class="field__hint">Минимум 8 символов.</div>
            @enderror
        </div>

        <div class="field">
            <label class="field__label" for="password_confirmation">Повторите пароль</label>
            <input class="field__control" type="password" id="password_confirmation"
                   name="password_confirmation" placeholder="••••••••">
        </div>

        <button class="btn" type="submit"><i class="bi bi-person-plus-fill"></i> Зарегистрироваться</button>
    </form>

    <p class="auth__switch">
        Уже зарегистрированы?
        <a href="{{ route('login') }}">Вход</a>
    </p>
    </div>
@endsection
