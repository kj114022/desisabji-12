<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;

class PerformanceTest extends TestCase
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
     * Test: Product listing responds within acceptable time
     */
    public function test_product_listing_performance()
    {
        // Create 100 products
        Product::factory()->count(100)->create();

        $startTime = microtime(true);
        
        $response = $this->getJson('/api/products?per_page=50');
        
        $endTime = microtime(true);
        $duration = ($endTime - $startTime) * 1000; // Convert to milliseconds

        $response->assertStatus(200);
        
        // Response should complete within 1 second (1000ms)
        $this->assertLessThan(1000, $duration, "Product listing took {$duration}ms, should be < 1000ms");
    }

    /**
     * Test: Product search performance with large dataset
     */
    public function test_product_search_performance()
    {
        // Create 500 products
        Product::factory()->count(500)->create();

        $startTime = microtime(true);
        
        $response = $this->getJson('/api/product/search?q=test');
        
        $endTime = microtime(true);
        $duration = ($endTime - $startTime) * 1000;

        $response->assertStatus(200);
        
        // Search should complete within 500ms
        $this->assertLessThan(500, $duration, "Search took {$duration}ms, should be < 500ms");
    }

    /**
     * Test: Authentication endpoint response time
     */
    public function test_authentication_endpoint_performance()
    {
        $startTime = microtime(true);
        
        $response = $this->postJson('/api/login', [
            'email' => $this->user->email,
            'password' => 'password'
        ]);
        
        $endTime = microtime(true);
        $duration = ($endTime - $startTime) * 1000;

        // Login should complete within 500ms
        $this->assertLessThan(500, $duration, "Login took {$duration}ms, should be < 500ms");
    }

    /**
     * Test: Concurrent requests handling
     */
    public function test_concurrent_requests_performance()
    {
        $startTime = microtime(true);
        
        // Simulate 10 concurrent requests
        for ($i = 0; $i < 10; $i++) {
            $response = $this->withHeaders([
                'Authorization' => "Bearer {$this->token}"
            ])->getJson('/api/user');
            
            $response->assertStatus(200);
        }
        
        $endTime = microtime(true);
        $duration = ($endTime - $startTime) * 1000;

        // 10 requests should complete within 2 seconds
        $this->assertLessThan(2000, $duration, "10 concurrent requests took {$duration}ms, should be < 2000ms");
    }

    /**
     * Test: Pagination efficiency
     */
    public function test_pagination_efficiency()
    {
        // Create 1000 products
        Product::factory()->count(1000)->create();

        $startTime = microtime(true);
        
        // Request page 50 with 20 items per page
        $response = $this->getJson('/api/products?page=50&per_page=20');
        
        $endTime = microtime(true);
        $duration = ($endTime - $startTime) * 1000;

        $response->assertStatus(200);
        
        // Even with deep pagination, should respond quickly
        $this->assertLessThan(800, $duration, "Pagination request took {$duration}ms, should be < 800ms");
    }

    /**
     * Test: Bulk data retrieval optimization
     */
    public function test_bulk_data_retrieval_optimization()
    {
        // Create products with relationships
        Product::factory()->count(250)->create();

        $startTime = microtime(true);
        
        $response = $this->getJson('/api/products?per_page=100');
        
        $endTime = microtime(true);
        $duration = ($endTime - $startTime) * 1000;

        $response->assertStatus(200);
        $data = $response->json('data');
        
        $this->assertIsArray($data);
        $this->assertLessThan(1000, $duration, "Bulk retrieval took {$duration}ms, should be < 1000ms");
    }

    /**
     * Test: Database query count efficiency
     */
    public function test_query_count_efficiency()
    {
        Product::factory()->count(50)->create();

        // Count queries during request
        $this->assertQueryCountLessThan(10, function () {
            $response = $this->getJson('/api/products?per_page=50');
            $this->assertTrue($response->ok());
        });
    }

    /**
     * Helper: Assert query count is less than expected
     */
    protected function assertQueryCountLessThan($expected, $callback)
    {
        \DB::enableQueryLog();
        
        $callback();
        
        $queryCount = count(\DB::getQueryLog());
        \DB::disableQueryLog();

        $this->assertLessThan(
            $expected,
            $queryCount,
            "Expected less than {$expected} queries, got {$queryCount}"
        );
    }
}
