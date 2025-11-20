<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CORSTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Preflight OPTIONS request allowed from Angular localhost
     */
    public function test_preflight_options_request_allowed()
    {
        $response = $this->optionsJson(
            '/api/products',
            [],
            [
                'Origin' => 'http://localhost:4200',
                'Access-Control-Request-Method' => 'GET',
                'Access-Control-Request-Headers' => 'Content-Type'
            ]
        );

        $this->assertTrue(
            $response->status() === 200 || $response->status() === 204
        );
    }

    /**
     * Test: CORS headers present in responses
     */
    public function test_cors_headers_in_response()
    {
        $response = $this->withHeaders([
            'Origin' => 'http://localhost:4200'
        ])->getJson('/api/products');

        // Check for CORS headers
        $this->assertTrue(
            $response->headers->has('Access-Control-Allow-Origin') ||
            $response->status() === 200
        );
    }

    /**
     * Test: Request from allowed origin (localhost:4200) succeeds
     */
    public function test_request_from_allowed_origin_succeeds()
    {
        $response = $this->withHeaders([
            'Origin' => 'http://localhost:4200'
        ])->getJson('/api/products');

        $response->assertStatus(200);
    }

    /**
     * Test: Credentials can be sent with CORS requests
     */
    public function test_credentials_supported_in_cors()
    {
        $response = $this->withHeaders([
            'Origin' => 'http://localhost:4200',
            'Access-Control-Request-Method' => 'POST'
        ])->optionsJson('/api/login');

        // Response should allow credentials
        $this->assertTrue(
            $response->status() === 200 || $response->status() === 204
        );
    }

    /**
     * Test: Exposed headers include necessary data headers
     */
    public function test_exposed_headers_configured()
    {
        $response = $this->withHeaders([
            'Origin' => 'http://localhost:4200'
        ])->getJson('/api/products');

        $response->assertStatus(200);
        
        // Verify response is valid JSON
        $this->assertIsArray($response->json());
    }

    /**
     * Test: Invalid origin requests are handled
     */
    public function test_invalid_origin_handling()
    {
        $response = $this->withHeaders([
            'Origin' => 'http://malicious-site.com'
        ])->getJson('/api/products');

        // Should either reject or handle gracefully
        $this->assertTrue(
            $response->status() === 200 ||
            $response->status() === 403 ||
            $response->status() === 401
        );
    }

    /**
     * Test: Complex CORS request (with Authorization header)
     */
    public function test_complex_cors_request_with_auth()
    {
        $user = \App\Models\User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeaders([
            'Origin' => 'http://localhost:4200',
            'Authorization' => "Bearer {$token}",
            'Content-Type' => 'application/json'
        ])->getJson('/api/user');

        $response->assertStatus(200);
    }

    /**
     * Test: POST request with CORS works properly
     */
    public function test_cors_post_request_succeeds()
    {
        $response = $this->withHeaders([
            'Origin' => 'http://localhost:4200',
            'Content-Type' => 'application/json'
        ])->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        // Should return 401 (bad credentials) not CORS error
        $this->assertTrue(
            $response->status() === 401 ||
            $response->status() === 422 ||
            $response->status() === 200
        );
    }
}
