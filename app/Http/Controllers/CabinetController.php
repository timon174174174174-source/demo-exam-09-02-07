<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class CabinetController extends Controller
{
    /** Личный кабинет: история заявок пользователя и отзывы. */
    public function index(Request $request): View
    {
        $bookings = $request->user()
            ->bookings()
            ->with(['room', 'review'])
            ->get();

        return view('cabinet.index', compact('bookings'));
    }
}
