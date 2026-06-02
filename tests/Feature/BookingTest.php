<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    private function makeRoom(): Room
    {
        return Room::create(['name' => 'Аудитория', 'type' => 'Аудитория', 'capacity' => 50]);
    }

    public function test_guest_is_redirected_from_booking_form(): void
    {
        $this->get('/bookings/create')->assertRedirect(route('login'));
    }

    public function test_user_can_create_booking_with_new_status(): void
    {
        $user = User::factory()->create();
        $room = $this->makeRoom();

        $response = $this->actingAs($user)->post('/bookings', [
            'room_id' => $room->id,
            'event_date' => now()->addDays(5)->format('Y-m-d'),
            'payment_method' => 'Наличные',
        ]);

        $response->assertRedirect(route('cabinet'));
        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'room_id' => $room->id,
            'status' => Booking::STATUS_NEW,
        ]);
    }

    public function test_booking_accepts_ddmmyyyy_date_format(): void
    {
        $user = User::factory()->create();
        $room = $this->makeRoom();

        $this->actingAs($user)->post('/bookings', [
            'room_id' => $room->id,
            'event_date' => now()->addDays(5)->format('d.m.Y'),
            'payment_method' => 'Наличные',
        ])->assertRedirect(route('cabinet'));

        $this->assertDatabaseCount('bookings', 1);
    }

    public function test_past_date_is_rejected(): void
    {
        $user = User::factory()->create();
        $room = $this->makeRoom();

        $this->actingAs($user)->from('/bookings/create')->post('/bookings', [
            'room_id' => $room->id,
            'event_date' => now()->subDays(2)->format('Y-m-d'),
            'payment_method' => 'Наличные',
        ])->assertSessionHasErrors('event_date');

        $this->assertDatabaseCount('bookings', 0);
    }
}
