@extends('layouts.app')

@section('title', 'Личный кабинет')

@section('content')
    <h1 class="page-title">Здравствуйте, {{ auth()->user()->full_name }}!</h1>
    <p class="page-subtitle">Здесь собраны ваши заявки на бронирование помещений.</p>

    <div class="slider" data-slider>
        <div class="slider__track">
            @foreach (['slide1', 'slide2', 'slide3', 'slide4'] as $slide)
                <div class="slider__slide">
                    <img src="{{ asset('images/slides/'.$slide.'.svg') }}" alt="Конференции.РФ" loading="lazy">
                </div>
            @endforeach
        </div>
        <button type="button" class="slider__btn slider__btn--prev" aria-label="Назад"><i class="bi bi-chevron-left"></i></button>
        <button type="button" class="slider__btn slider__btn--next" aria-label="Вперёд"><i class="bi bi-chevron-right"></i></button>
        <div class="slider__dots">
            @for ($i = 0; $i < 4; $i++)
                <button type="button" class="slider__dot {{ $i === 0 ? 'is-active' : '' }}" aria-label="Слайд {{ $i + 1 }}"></button>
            @endfor
        </div>
    </div>

    <a href="{{ route('bookings.create') }}" class="btn btn--inline">
        <i class="bi bi-plus-circle"></i> Оформить новую заявку
    </a>

    <h2 class="section-title"><i class="bi bi-clock-history"></i> История заявок</h2>

    <div class="grid">
    @forelse ($bookings as $booking)
        <article class="booking" style="animation-delay: {{ $loop->index * 60 }}ms">
            <div class="booking__head">
                <div class="booking__room"><i class="bi bi-building"></i> {{ $booking->room->name }}</div>
                <span class="badge badge--{{ $booking->status }}">{{ $booking->statusLabel() }}</span>
            </div>

            <div class="booking__meta">
                <div><i class="bi bi-calendar-event"></i> {{ $booking->event_date->format('d.m.Y') }}</div>
                <div><i class="bi bi-credit-card"></i> {{ $booking->payment_method }}</div>
                <div><i class="bi bi-people"></i> до {{ $booking->room->capacity }} чел.</div>
            </div>

            @if ($booking->review)
                <div class="review-box">
                    <div class="stars-static">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="bi {{ $i <= $booking->review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>
                        @endfor
                    </div>
                    <p class="review-saved"><i class="bi bi-chat-quote"></i> {{ $booking->review->body }}</p>
                </div>
            @elseif ($booking->canBeReviewed())
                <form method="POST" action="{{ route('reviews.store') }}" class="review-box">
                    @csrf
                    <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                    <label class="field__label">Оцените услугу</label>
                    <div class="stars">
                        @for ($i = 5; $i >= 1; $i--)
                            <input type="radio" id="r{{ $booking->id }}-{{ $i }}" name="rating" value="{{ $i }}" @checked($i === 5)>
                            <label for="r{{ $booking->id }}-{{ $i }}"><i class="bi bi-star-fill"></i></label>
                        @endfor
                    </div>
                    <textarea name="body" class="field__control" rows="2"
                              placeholder="Поделитесь впечатлениями об услуге..."></textarea>
                    @error('body')
                        <div class="field__error"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                    @enderror
                    <button type="submit" class="btn btn--sm" style="margin-top:10px">
                        <i class="bi bi-send"></i> Оставить отзыв
                    </button>
                </form>
            @else
                <div class="review-box review-saved">
                    <i class="bi bi-hourglass-split"></i>
                    Отзыв можно оставить после подтверждения заявки администратором.
                </div>
            @endif
        </article>
    @empty
        <div class="empty">
            <i class="bi bi-calendar-x"></i>
            <p>У вас пока нет заявок.</p>
            <a href="{{ route('bookings.create') }}" class="btn btn--sm">Оформить первую заявку</a>
        </div>
    @endforelse
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/slider.js') }}"></script>
@endpush
