<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    private function makeBooking(User $user, string $status): Booking
    {
        $room = Room::create(['name' => 'Коворкинг', 'type' => 'Коворкинг', 'capacity' => 30]);

        return Booking::create([
            'user_id' => $user->id,
            'room_id' => $room->id,
            'event_date' => now()->addDays(3),
            'payment_method' => 'Наличные',
            'status' => $status,
        ]);
    }

    public function test_cannot_review_new_booking(): void
    {
        $user = User::factory()->create();
        $booking = $this->makeBooking($user, Booking::STATUS_NEW);

        $this->actingAs($user)
            ->post('/reviews', ['booking_id' => $booking->id, 'rating' => 5, 'body' => 'Отлично!'])
            ->assertForbidden();

        $this->assertDatabaseCount('reviews', 0);
    }

    public function test_can_review_after_admin_changed_status(): void
    {
        $user = User::factory()->create();
        $booking = $this->makeBooking($user, Booking::STATUS_COMPLETED);

        $this->actingAs($user)
            ->post('/reviews', ['booking_id' => $booking->id, 'rating' => 5, 'body' => 'Всё прошло отлично!'])
            ->assertRedirect(route('cabinet'));

        $this->assertDatabaseHas('reviews', ['booking_id' => $booking->id, 'rating' => 5]);
    }

    public function test_cannot_review_someone_elses_booking(): void
    {
        $user = User::factory()->create();
        $owner = User::factory()->create();
        $booking = $this->makeBooking($owner, Booking::STATUS_COMPLETED);

        $this->actingAs($user)
            ->post('/reviews', ['booking_id' => $booking->id, 'rating' => 5, 'body' => 'Чужая заявка'])
            ->assertForbidden();

        $this->assertDatabaseCount('reviews', 0);
    }
}
