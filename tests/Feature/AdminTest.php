<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    private function makeBooking(User $user): Booking
    {
        $room = Room::create(['name' => 'Кинозал', 'type' => 'Кинозал', 'capacity' => 100]);

        return Booking::create([
            'user_id' => $user->id,
            'room_id' => $room->id,
            'event_date' => now()->addDays(3),
            'payment_method' => 'Банковская карта',
            'status' => Booking::STATUS_NEW,
        ]);
    }

    public function test_regular_user_cannot_open_admin_panel(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    public function test_admin_can_open_panel(): void
    {
        $admin = User::factory()->admin()->create();
        $this->actingAs($admin)->get('/admin')->assertOk()->assertSee('Панель администратора');
    }

    public function test_admin_can_change_booking_status(): void
    {
        $admin = User::factory()->admin()->create();
        $booking = $this->makeBooking(User::factory()->create());

        $this->actingAs($admin)
            ->patch("/admin/bookings/{$booking->id}/status", ['status' => Booking::STATUS_ASSIGNED])
            ->assertRedirect();

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => Booking::STATUS_ASSIGNED,
        ]);
    }

    public function test_invalid_status_is_rejected(): void
    {
        $admin = User::factory()->admin()->create();
        $booking = $this->makeBooking(User::factory()->create());

        $this->actingAs($admin)
            ->from('/admin')
            ->patch("/admin/bookings/{$booking->id}/status", ['status' => 'unknown'])
            ->assertSessionHasErrors('status');
    }
}
