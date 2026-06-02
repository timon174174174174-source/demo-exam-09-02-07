@extends('layouts.app')

@section('title', 'Новая заявка')

@push('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css">
@endpush

@section('content')
    <div class="narrow">
    <h1 class="page-title">Оформление заявки</h1>
    <p class="page-subtitle">Забронируйте помещение для проведения конференции.</p>

    <form method="POST" action="{{ route('bookings.store') }}" class="card" novalidate>
        @csrf

        <div class="field {{ $errors->has('room_id') ? 'field--invalid' : '' }}">
            <label class="field__label" for="room_id">Помещение</label>
            <select class="field__control" id="room_id" name="room_id">
                <option value="" disabled {{ old('room_id') ? '' : 'selected' }}>— выберите помещение —</option>
                @foreach ($rooms as $room)
                    <option value="{{ $room->id }}"
                            data-image="{{ asset($room->image) }}"
                            data-desc="{{ $room->description }}"
                            @selected(old('room_id') == $room->id)>
                        {{ $room->name }} (до {{ $room->capacity }} чел.)
                    </option>
                @endforeach
            </select>
            @error('room_id')
                <div class="field__error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
            @enderror
        </div>

        <div class="room-preview" id="roomPreview" hidden>
            <img id="roomPreviewImg" src="" alt="Помещение">
            <p id="roomPreviewDesc" class="field__hint"></p>
        </div>

        <div class="field {{ $errors->has('event_date') ? 'field--invalid' : '' }}">
            <label class="field__label" for="event_date">Дата начала конференции</label>
            <input class="field__control" type="text" id="event_date" name="event_date"
                   value="{{ old('event_date') }}" placeholder="ДД.ММ.ГГГГ" autocomplete="off">
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
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/l10n/ru.js"></script>
    <script>
        // Календарь в формате ДД.ММ.ГГГГ (на сервер уходит дата как Y-m-d)
        if (window.flatpickr) {
            flatpickr('#event_date', {
                altInput: true,
                altFormat: 'd.m.Y',
                dateFormat: 'Y-m-d',
                minDate: 'today',
                locale: 'ru',
                disableMobile: true,
            });
        }

        // Предпросмотр выбранного помещения
        (function () {
            const sel = document.getElementById('room_id');
            const box = document.getElementById('roomPreview');
            const img = document.getElementById('roomPreviewImg');
            const desc = document.getElementById('roomPreviewDesc');
            if (!sel) return;
            function update() {
                const opt = sel.options[sel.selectedIndex];
                if (opt && opt.value && opt.dataset.image) {
                    img.src = opt.dataset.image;
                    desc.textContent = opt.dataset.desc || '';
                    box.hidden = false;
                } else {
                    box.hidden = true;
                }
            }
            sel.addEventListener('change', update);
            update();
        })();
    </script>
@endpush
