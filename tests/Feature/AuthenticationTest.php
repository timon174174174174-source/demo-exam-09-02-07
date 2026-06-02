<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_renders(): void
    {
        $this->get('/login')->assertOk()->assertSee('Войти');
    }

    public function test_user_can_login_with_correct_credentials(): void
    {
        $user = User::factory()->create(['login' => 'tester1', 'password' => 'password123']);

        $response = $this->post('/login', ['login' => 'tester1', 'password' => 'password123']);

        $response->assertRedirect(route('cabinet'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        User::factory()->create(['login' => 'tester1', 'password' => 'password123']);

        $response = $this->from('/login')->post('/login', ['login' => 'tester1', 'password' => 'wrong-pass']);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    public function test_admin_is_redirected_to_dashboard(): void
    {
        User::factory()->admin()->create(['login' => 'Admin26', 'password' => 'Demo20']);

        $response = $this->post('/login', ['login' => 'Admin26', 'password' => 'Demo20']);

        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/logout')->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
