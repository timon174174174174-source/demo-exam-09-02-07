@extends('layouts.app')

@section('title', 'Панель администратора')

@section('content')
    <h1 class="page-title">Панель администратора</h1>
    <p class="page-subtitle">Все заявки на бронирование и управление их статусами.</p>

    <div class="stats">
        <div class="stat"><div class="stat__num">{{ $stats['total'] }}</div><div class="stat__label">Всего заявок</div></div>
        <div class="stat stat--new"><div class="stat__num">{{ $stats['new'] }}</div><div class="stat__label">Новые</div></div>
        <div class="stat stat--assigned"><div class="stat__num">{{ $stats['assigned'] }}</div><div class="stat__label">Назначены</div></div>
        <div class="stat stat--completed"><div class="stat__num">{{ $stats['completed'] }}</div><div class="stat__label">Завершены</div></div>
    </div>

    <form method="GET" action="{{ route('admin.dashboard') }}" class="filters">
        <div class="field">
            <label class="field__label">Статус</label>
            <select name="status" class="field__control">
                <option value="">Все статусы</option>
                @foreach ($statuses as $code => $label)
                    <option value="{{ $code }}" @selected(request('status') === $code)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="field">
            <label class="field__label">Помещение</label>
            <select name="room_id" class="field__control">
                <option value="">Все помещения</option>
                @foreach ($rooms as $room)
                    <option value="{{ $room->id }}" @selected((string) request('room_id') === (string) $room->id)>{{ $room->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="field">
            <label class="field__label">Сортировать</label>
            <select name="sort" class="field__control">
                <option value="created_at" @selected($sort === 'created_at')>По дате создания</option>
                <option value="event_date" @selected($sort === 'event_date')>По дате мероприятия</option>
                <option value="status" @selected($sort === 'status')>По статусу</option>
            </select>
        </div>
        <div class="field">
            <label class="field__label">Порядок</label>
            <select name="dir" class="field__control">
                <option value="desc" @selected($dir === 'desc')>Сначала новые</option>
                <option value="asc" @selected($dir === 'asc')>Сначала старые</option>
            </select>
        </div>
        <div class="filters__actions">
            <button type="submit" class="btn btn--sm"><i class="bi bi-funnel"></i> Применить</button>
            <a href="{{ route('admin.dashboard') }}" class="btn btn--sm btn--ghost">Сбросить</a>
        </div>
    </form>

    @forelse ($bookings as $booking)
        <article class="booking admin-booking" style="animation-delay: {{ $loop->index * 60 }}ms">
            <div class="admin-booking__top">
                <div class="admin-booking__user">
                    {{ $booking->user->full_name }}
                    <small>{{ '@'.$booking->user->login }} · {{ $booking->user->phone }}</small>
                </div>
                <span class="badge badge--{{ $booking->status }}">{{ $booking->statusLabel() }}</span>
            </div>

            <div class="booking__meta">
                <div><i class="bi bi-building"></i> {{ $booking->room->name }}</div>
                <div><i class="bi bi-calendar-event"></i> {{ $booking->event_date->format('d.m.Y') }}</div>
                <div><i class="bi bi-credit-card"></i> {{ $booking->payment_method }}</div>
                <div><i class="bi bi-hash"></i> заявка №{{ $booking->id }}, создана {{ $booking->created_at->format('d.m.Y') }}</div>
            </div>

            <form method="POST" action="{{ route('admin.bookings.status', $booking) }}" class="status-form">
                @csrf
                @method('PATCH')
                <label class="field__label">Изменить статус</label>
                <select name="status" class="field__control" onchange="this.form.submit()">
                    @foreach ($statuses as $code => $label)
                        <option value="{{ $code }}" @selected($booking->status === $code)>{{ $label }}</option>
                    @endforeach
                </select>
            </form>

            @if ($booking->review)
                <div class="review-box">
                    <div class="stars-static">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="bi {{ $i <= $booking->review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                        @endfor
                    </div>
                    <p class="review-saved"><i class="bi bi-chat-quote"></i> {{ $booking->review->body }}</p>
                </div>
            @endif
        </article>
    @empty
        <div class="empty">
            <i class="bi bi-inbox"></i>
            <p>Заявок по заданным условиям не найдено.</p>
        </div>
    @endforelse

    @if ($bookings->hasPages())
        <div class="pager">
            @if ($bookings->onFirstPage())
                <span class="disabled"><i class="bi bi-chevron-left"></i> Назад</span>
            @else
                <a href="{{ $bookings->previousPageUrl() }}"><i class="bi bi-chevron-left"></i> Назад</a>
            @endif

            <span class="pager__info">Стр. {{ $bookings->currentPage() }} из {{ $bookings->lastPage() }}</span>

            @if ($bookings->hasMorePages())
                <a href="{{ $bookings->nextPageUrl() }}">Вперёд <i class="bi bi-chevron-right"></i></a>
            @else
                <span class="disabled">Вперёд <i class="bi bi-chevron-right"></i></span>
            @endif
        </div>
    @endif
@endsection
