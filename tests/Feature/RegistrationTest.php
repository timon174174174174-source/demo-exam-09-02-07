<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_screen_renders(): void
    {
        $this->get('/register')->assertOk()->assertSee('Регистрация');
    }

    public function test_short_login_is_rejected(): void
    {
        $response = $this->from('/register')->post('/register', [
            'login' => 'ab',
            'full_name' => 'Тест Тестов',
            'phone' => '+7 900 0000000',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors('login');
        $this->assertDatabaseCount('users', 0);
    }

    public function test_login_with_cyrillic_is_rejected(): void
    {
        $response = $this->from('/register')->post('/register', [
            'login' => 'логин123',
            'full_name' => 'Тест',
            'phone' => '123',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('login');
        $this->assertDatabaseCount('users', 0);
    }

    public function test_user_can_register_and_is_logged_in(): void
    {
        $response = $this->post('/register', [
            'login' => 'newuser1',
            'full_name' => 'Новый Пользователь',
            'phone' => '+7 900 1112233',
            'email' => 'new@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('cabinet'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['login' => 'newuser1', 'is_admin' => false]);
        $this->assertNotSame('password123', User::first()->password); // пароль захеширован
    }
}
