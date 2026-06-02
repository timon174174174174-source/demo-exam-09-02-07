@extends('layouts.app')

@section('title', 'Новая заявка')

@section('content')
    <h1 class="page-title">Оформление заявки</h1>
    <p class="page-subtitle">Забронируйте помещение для проведения конференции.</p>

    <form method="POST" action="{{ route('bookings.store') }}" class="card" novalidate>
        @csrf

        <div class="field {{ $errors->has('room_id') ? 'field--invalid' : '' }}">
            <label class="field__label" for="room_id">Помещение</label>
            <select class="field__control" id="room_id" name="room_id">
                <option value="" disabled {{ old('room_id') ? '' : 'selected' }}>— выберите помещение —</option>
                @foreach ($rooms as $room)
                    <option value="{{ $room->id }}" @selected(old('room_id') == $room->id)>
                        {{ $room->name }} (до {{ $room->capacity }} чел.)
                    </option>
                @endforeach
            </select>
            @error('room_id')
                <div class="field__error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
            @enderror
        </div>

        <div class="field {{ $errors->has('event_date') ? 'field--invalid' : '' }}">
            <label class="field__label" for="event_date">Дата начала конференции</label>
            <input class="field__control" type="date" id="event_date" name="event_date"
                   value="{{ old('event_date') }}" min="{{ now()->toDateString() }}">
            @error('event_date')
                <div class="field__error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
            @enderror
        </div>

        <div class="field {{ $errors->has('payment_method') ? 'field--invalid' : '' }}">
            <label class="field__label" for="payment_method">Способ оплаты</label>
            <select class="field__control" id="payment_method" name="payment_method">
                <option value="" disabled {{ old('payment_method') ? '' : 'selected' }}>— выберите способ —</option>
                @foreach ($paymentMethods as $method)
                    <option value="{{ $method }}" @selected(old('payment_method') === $method)>{{ $method }}</option>
                @endforeach
            </select>
            @error('payment_method')
                <div class="field__error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
            @enderror
        </div>

        <button class="btn" type="submit"><i class="bi bi-send-check"></i> Отправить заявку</button>
    </form>
@endsection
