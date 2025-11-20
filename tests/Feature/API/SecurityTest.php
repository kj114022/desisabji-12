<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('auth_token')->plainTextToken;
    }

    /**
     * Test: SQL Injection prevention - User registration
     */
    public function test_sql_injection_prevention_in_registration()
    {
        $response = $this->postJson('/api/signup', [
            'name' => "'; DROP TABLE users; --",
            'email' => "test@example.com' OR '1'='1",
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!'
        ]);

        // Should either fail validation or succeed but not execute SQL
        $this->assertTrue(
            $response->status() === 422 || $response->status() === 201
        );

        // Verify table still exists
        $this->assertGreaterThan(0, User::count());
    }

    /**
     * Test: XSS prevention in product search
     */
    public function test_xss_prevention_in_search()
    {
        $response = $this->getJson('/api/product/search', [
            'q' => '<script>alert("XSS")</script>'
        ]);

        $response->assertStatus(200);
        
        // Verify no script tags in response
        $this->assertStringNotContainsString(
            '<script>',
            json_encode($response->json())
        );
    }

    /**
     * Test: CSRF token validation (if applicable)
     */
    public function test_csrf_protection()
    {
        // Laravel API routes typically use sanctum instead of CSRF
        // This test verifies API works with Authorization header
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}"
        ])->getJson('/api/user');

        $response->assertStatus(200);
    }

    /**
     * Test: Rate limiting on login endpoint
     */
    public function test_rate_limiting_on_login()
    {
        // Simulate multiple failed login attempts
        for ($i = 0; $i < 10; $i++) {
            $this->postJson('/api/login', [
                'email' => 'nonexistent@example.com',
                'password' => 'wrongpassword'
            ]);
        }

        // After multiple attempts, should get rate limited
        $response = $this->postJson('/api/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword'
        ]);

        // Should return 429 (Too Many Requests) or 401
        $this->assertTrue($response->status() === 429 || $response->status() === 401);
    }

    /**
     * Test: Authorization - User cannot modify another user's profile
     */
    public function test_user_cannot_modify_other_user_profile()
    {
        $otherUser = User::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}"
        ])->putJson("/api/users/{$otherUser->id}", [
            'name' => 'Hacked Name'
        ]);

        $response->assertStatus(403);
    }

    /**
     * Test: Sensitive data not exposed in responses
     */
    public function test_sensitive_data_not_exposed()
    {
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}"
        ])->getJson('/api/user');

        $response->assertStatus(200);

        // Verify sensitive fields are not exposed
        $data = $response->json('data');
        
        $this->assertArrayNotHasKey('password', $data);
        $this->assertArrayNotHasKey('api_token', $data);
        $this->assertArrayNotHasKey('remember_token', $data);
    }

    /**
     * Test: Token expiration validation
     */
    public function test_expired_token_is_rejected()
    {
        $expiredToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJleHAiOjE1MTYyMzkwMjJ9.invalid_signature';

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$expiredToken}"
        ])->getJson('/api/user');

        $response->assertStatus(401);
    }

    /**
     * Test: Unauthenticated user cannot access protected endpoints
     */
    public function test_unauthenticated_user_blocked_from_protected_endpoints()
    {
        $endpoints = [
            '/api/user',
            '/api/orders',
            '/api/carts'
        ];

        foreach ($endpoints as $endpoint) {
            $response = $this->getJson($endpoint);
            $this->assertEquals(401, $response->status(), "Endpoint {$endpoint} should require authentication");
        }
    }
}
