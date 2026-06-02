<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Учётная запись администратора по заданию: логин Admin26 / пароль Demo20.
        User::firstOrCreate(
            ['login' => 'Admin26'],
            [
                'full_name' => 'Администратор системы',
                'phone' => '+7 (900) 000-00-00',
                'email' => 'admin@conf.rf',
                'password' => 'Demo20', // будет захеширован автоматически (cast 'hashed')
                'is_admin' => true,
            ]
        );
    }
}
