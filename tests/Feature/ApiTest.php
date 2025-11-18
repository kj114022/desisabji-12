<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Market;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

/**
 * Comprehensive API Test Suite
 * 
 * Tests all API endpoints for the DesiSabji Services application
 * 
 * @package Tests\Feature
 */
class ApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a test user and authenticate
        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ]);
        
        // Authenticate using Sanctum
        Sanctum::actingAs($this->user);
    }

    /**
     * Test API authentication endpoints
     */
    public function test_auth_endpoints(): void
    {
        // Test login endpoint
        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password'
        ]);
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'token',
                    'user'
                ]
            ]);
    }

    /**
     * Test categories API endpoints
     */
    public function test_categories_endpoints(): void
    {
        // Create test categories
        $categories = Category::factory()->count(5)->create();

        // Test GET /api/categories
        $response = $this->getJson('/api/categories');
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description'
                    ]
                ]
            ]);
    }

    /**
     * Test markets API endpoints
     */
    public function test_markets_endpoints(): void
    {
        // Create test market
        $market = Market::factory()->create();

        // Test GET /api/markets
        $response = $this->getJson('/api/markets');
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data'
            ]);
    }

    /**
     * Test products API endpoints
     */
    public function test_products_endpoints(): void
    {
        // Create test data
        $category = Category::factory()->create();
        $market = Market::factory()->create();
        $products = Product::factory()->count(3)->create([
            'category_id' => $category->id,
            'market_id' => $market->id
        ]);

        // Test GET /api/products
        $response = $this->getJson('/api/products');
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data'
            ]);
    }

    /**
     * Test API response format consistency
     */
    public function test_api_response_format(): void
    {
        $response = $this->getJson('/api/categories');
        
        $response->assertStatus(200)
            ->assertJson([
                'success' => true
            ])
            ->assertJsonStructure([
                'success',
                'data'
            ]);
    }

    /**
     * Test API error handling
     */
    public function test_api_error_handling(): void
    {
        // Test 404 for non-existent resource
        $response = $this->getJson('/api/categories/99999');
        
        $response->assertStatus(404);
    }

    /**
     * Test API pagination
     */
    public function test_api_pagination(): void
    {
        Category::factory()->count(25)->create();

        $response = $this->getJson('/api/categories?per_page=10');
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'meta' => [
                    'current_page',
                    'per_page',
                    'total'
                ]
            ]);
    }
}

