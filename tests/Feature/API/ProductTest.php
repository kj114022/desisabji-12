<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $products;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->category = Category::factory()->create();
        $this->products = Product::factory(15)->create([
            'category_id' => $this->category->id
        ]);
    }

    /**
     * Test: Get all products
     */
    public function test_can_get_all_products()
    {
        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         '*' => ['id', 'name', 'description', 'price', 'category_id']
                     ]
                 ]);

        $this->assertCount(15, $response->json('data'));
    }

    /**
     * Test: Get products with pagination
     */
    public function test_can_get_products_with_pagination()
    {
        $response = $this->getJson('/api/products?page=1&limit=5');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data',
                     'pagination' => ['total', 'per_page', 'current_page']
                 ]);
    }

    /**
     * Test: Get single product by ID
     */
    public function test_can_get_single_product()
    {
        $product = $this->products->first();

        $response = $this->getJson("/api/products/{$product->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => ['id', 'name', 'description', 'price']
                 ])
                 ->assertJsonPath('data.id', $product->id);
    }

    /**
     * Test: Get non-existent product returns 404
     */
    public function test_get_non_existent_product_returns_404()
    {
        $response = $this->getJson('/api/products/99999');

        $response->assertStatus(404);
    }

    /**
     * Test: Get all categories
     */
    public function test_can_get_categories()
    {
        $response = $this->getJson('/api/products/categories');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data'
                 ]);
    }

    /**
     * Test: Get products by category
     */
    public function test_can_get_products_by_category()
    {
        $response = $this->getJson("/api/products/categories/{$this->category->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data'
                 ]);
    }

    /**
     * Test: Search products
     */
    public function test_can_search_products()
    {
        $product = $this->products->first();
        $searchQuery = substr($product->name, 0, 3);

        $response = $this->getJson("/api/product/search?q={$searchQuery}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data'
                 ]);
    }

    /**
     * Test: Search with empty query returns error
     */
    public function test_search_with_empty_query_returns_error()
    {
        $response = $this->getJson('/api/product/search?q=');

        $response->assertStatus(400);
    }
}
