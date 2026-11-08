<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_login_page(): void
    {
        $response = $this->get('/connexion');

        $response->assertStatus(200);
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'student@test.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/connexion', [
            'email' => 'student@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/mes-reservations');
        $this->assertAuthenticatedAs($user);
    }

    public function test_guest_redirected_to_login_when_accessing_bookings(): void
    {
        $response = $this->get('/mes-reservations');

        $response->assertRedirect('/connexion');
    }
}
