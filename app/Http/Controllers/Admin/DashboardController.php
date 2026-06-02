<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /** Колонки, по которым разрешена сортировка. */
    private const SORTABLE = ['created_at', 'event_date', 'status'];

    /** Панель администратора: все заявки с фильтрами, сортировкой и пагинацией. */
    public function index(Request $request): View
    {
        $sort = in_array($request->get('sort'), self::SORTABLE, true)
            ? $request->get('sort')
            : 'created_at';
        $dir = $request->get('dir') === 'asc' ? 'asc' : 'desc';

        $bookings = Booking::query()
            ->with(['user', 'room', 'review'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('room_id'), fn ($q) => $q->where('room_id', $request->integer('room_id')))
            ->orderBy($sort, $dir)
            ->paginate(8)
            ->withQueryString();

        $rooms = Room::orderBy('name')->get();
        $statuses = Booking::STATUSES;

        $stats = [
            'total' => Booking::count(),
            Booking::STATUS_NEW => Booking::where('status', Booking::STATUS_NEW)->count(),
            Booking::STATUS_ASSIGNED => Booking::where('status', Booking::STATUS_ASSIGNED)->count(),
            Booking::STATUS_COMPLETED => Booking::where('status', Booking::STATUS_COMPLETED)->count(),
        ];

        return view('admin.dashboard', compact('bookings', 'rooms', 'statuses', 'stats', 'sort', 'dir'));
    }

    /** Смена статуса заявки администратором. */
    public function updateStatus(Request $request, Booking $booking): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(Booking::STATUSES))],
        ], [
            'status.required' => 'Не указан статус.',
            'status.in' => 'Недопустимый статус.',
        ]);

        $booking->update(['status' => $data['status']]);

        return back()->with('success', "Статус заявки №{$booking->id} изменён на «{$booking->statusLabel()}».");
    }
}
