<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoomSeeder::class,
            AdminSeeder::class,
        ]);

        // Демонстрационный пользователь
        $user = User::firstOrCreate(
            ['login' => 'user123'],
            [
                'full_name' => 'Иванов Иван Иванович',
                'phone' => '+7 (912) 345-67-89',
                'email' => 'ivanov@example.com',
                'password' => 'password123',
                'is_admin' => false,
            ]
        );

        // Демонстрационные заявки по одной на каждый статус (только при первом запуске)
        if ($user->bookings()->count() === 0) {
            $rooms = Room::all()->keyBy('type');

            Booking::create([
                'user_id' => $user->id,
                'room_id' => $rooms['Аудитория']->id,
                'event_date' => now()->addDays(14)->toDateString(),
                'payment_method' => 'Банковская карта',
                'status' => Booking::STATUS_NEW,
            ]);

            Booking::create([
                'user_id' => $user->id,
                'room_id' => $rooms['Коворкинг']->id,
                'event_date' => now()->addDays(7)->toDateString(),
                'payment_method' => 'Безналичный расчёт',
                'status' => Booking::STATUS_ASSIGNED,
            ]);

            Booking::create([
                'user_id' => $user->id,
                'room_id' => $rooms['Кинозал']->id,
                'event_date' => now()->subDays(3)->toDateString(),
                'payment_method' => 'Наличные',
                'status' => Booking::STATUS_COMPLETED,
            ]);
        }
    }
}
