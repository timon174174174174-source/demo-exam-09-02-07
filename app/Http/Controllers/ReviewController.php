<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    /** Сохранение отзыва к заявке (только после согласования администратором). */
    public function store(StoreReviewRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $booking = Booking::findOrFail($data['booking_id']);

        // отзыв можно оставить только к своей заявке
        abort_unless($booking->user_id === $request->user()->id, 403);
        // и только после смены статуса администратором (и один раз)
        abort_unless($booking->canBeReviewed(), 403, 'Отзыв к этой заявке сейчас недоступен.');

        $booking->review()->create([
            'user_id' => $request->user()->id,
            'rating' => $data['rating'],
            'body' => $data['body'],
        ]);

        return redirect()->route('cabinet')
            ->with('success', 'Спасибо! Ваш отзыв сохранён.');
    }
}
