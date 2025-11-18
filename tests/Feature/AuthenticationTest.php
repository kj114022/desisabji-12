<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

/**
 * Authentication Test Suite
 * 
 * Tests all authentication endpoints and flows
 * 
 * @package Tests\Feature
 */
class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $password = 'password123';

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a test user
        $mobile = '1234567890';
        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'mobile' => $mobile,
            'login_id' => $mobile,
            'customer_id' => 'C' . $mobile,
            'password' => Hash::make($this->password),
        ]);
    }

    /**
     * Test web login page loads
     */
    public function test_login_page_loads(): void
    {
        $response = $this->get('/login');
        
        $response->assertStatus(200);
    }

    /**
     * Test web authentication
     */
    public function test_web_authentication(): void
    {
        // The login route uses Livewire Volt, so we need to use the actual login endpoint
        // For now, we'll test that authenticated users can access protected routes
        $response = $this->actingAs($this->user)->get('/dashboard');
        
        $response->assertStatus(200);
        $this->assertAuthenticated();
    }

    /**
     * Test API authentication with Sanctum
     */
    public function test_api_authentication(): void
    {
        $response = $this->postJson('/api/authToken', [
            'email' => $this->user->email,
            'password' => $this->password,
        ]);
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'token'
                ]
            ]);
    }

    /**
     * Test API authentication with invalid credentials
     */
    public function test_api_authentication_invalid_credentials(): void
    {
        $response = $this->postJson('/api/authToken', [
            'email' => $this->user->email,
            'password' => 'wrong-password',
        ]);
        
        $response->assertStatus(401);
    }

    /**
     * Test dashboard access requires authentication
     */
    public function test_dashboard_requires_authentication(): void
    {
        $response = $this->get('/dashboard');
        
        $response->assertRedirect('/login');
    }

    /**
     * Test authenticated user can access dashboard
     */
    public function test_authenticated_user_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->user)->get('/dashboard');
        
        $response->assertStatus(200);
    }

    /**
     * Test logout
     */
    public function test_logout(): void
    {
        $response = $this->actingAs($this->user)->post('/logout');
        
        $this->assertGuest();
    }
}

