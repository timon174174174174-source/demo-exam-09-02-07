<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            [
                'name' => 'Аудитория',
                'type' => 'Аудитория',
                'capacity' => 60,
                'description' => 'Классическая аудитория с рядами кресел, проектором и трибуной — для докладов и пленарных сессий.',
                'image' => 'images/rooms/auditorium.svg',
            ],
            [
                'name' => 'Коворкинг',
                'type' => 'Коворкинг',
                'capacity' => 30,
                'description' => 'Гибкое пространство с зонами для групповой работы, Wi-Fi и кофе-поинтом — для воркшопов и нетворкинга.',
                'image' => 'images/rooms/coworking.svg',
            ],
            [
                'name' => 'Кинозал',
                'type' => 'Кинозал',
                'capacity' => 120,
                'description' => 'Зал с большим экраном и качественным звуком — для презентаций, показов и крупных выступлений.',
                'image' => 'images/rooms/cinema.svg',
            ],
        ];

        foreach ($rooms as $room) {
            Room::firstOrCreate(['name' => $room['name']], $room);
        }
    }
}
