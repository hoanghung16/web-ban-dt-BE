<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token', 'user', 'message'])
            ->assertJsonPath('user.email', 'test@example.com');
    }

    public function test_login_validates_email()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    public function test_login_validates_password()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401);
    }

    public function test_user_can_register()
    {
        $response = $this->postJson('/api/auth/register', [
            'fullname' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('user.email', 'newuser@example.com');

        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
        ]);
    }

    public function test_register_validates_email_unique()
    {
        User::factory()->create([
            'email' => 'existing@example.com',
        ]);

        $response = $this->postJson('/api/auth/register', [
            'fullname' => 'New User',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    public function test_register_validates_password_confirmation()
    {
        $response = $this->postJson('/api/auth/register', [
            'fullname' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('password');
    }

    public function test_authenticated_user_can_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/auth/logout');

        $response->assertStatus(200);
    }

    public function test_get_current_user_requires_auth()
    {
        $response = $this->getJson('/api/auth/me');
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_get_own_profile()
    {
        $user = User::factory()->create();
        $token = $user->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/auth/me');

        $response->assertStatus(200)
            ->assertJsonPath('user.email', $user->email);
    }
}
