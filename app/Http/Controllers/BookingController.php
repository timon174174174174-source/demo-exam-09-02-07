<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BookingController extends Controller
{
    /** Форма оформления заявки. */
    public function create(): View
    {
        $rooms = Room::orderBy('name')->get();
        $paymentMethods = Booking::PAYMENT_METHODS;

        return view('bookings.create', compact('rooms', 'paymentMethods'));
    }

    /** Сохранение заявки со статусом «Новая» и отправка на согласование. */
    public function store(StoreBookingRequest $request): RedirectResponse
    {
        $request->user()->bookings()->create(
            $request->validated() + ['status' => Booking::STATUS_NEW]
        );

        return redirect()->route('cabinet')
            ->with('success', 'Заявка отправлена администратору на согласование.');
    }
}
