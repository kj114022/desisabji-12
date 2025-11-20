<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test: User can register successfully
     */
    public function test_user_can_register()
    {
        $response = $this->postJson('/api/signup', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => ['user', 'token']
                 ]);

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    /**
     * Test: Registration fails with invalid data
     */
    public function test_registration_fails_with_invalid_email()
    {
        $response = $this->postJson('/api/signup', [
            'name' => 'Test User',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    /**
     * Test: Registration fails when email already exists
     */
    public function test_registration_fails_with_existing_email()
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->postJson('/api/signup', [
            'name' => 'Test User',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test: User can login successfully
     */
    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => ['user', 'token']
                 ]);
    }

    /**
     * Test: Login fails with incorrect password
     */
    public function test_login_fails_with_incorrect_password()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test: Login fails with non-existent email
     */
    public function test_login_fails_with_non_existent_email()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test: User can get current user
     */
    public function test_user_can_get_current_user()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->getJson('/api/user');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => ['id', 'name', 'email']
                 ]);
    }

    /**
     * Test: User cannot get current user without token
     */
    public function test_user_cannot_get_current_user_without_token()
    {
        $response = $this->getJson('/api/user');

        $response->assertStatus(401);
    }

    /**
     * Test: User can logout
     */
    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->getJson('/api/logout');

        $response->assertStatus(200);
    }
}
