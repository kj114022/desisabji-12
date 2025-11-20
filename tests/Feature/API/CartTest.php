<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;

class CartTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $product;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('auth_token')->plainTextToken;
        $this->product = Product::factory()->create(['price' => 99.99]);
    }

    /**
     * Test: Get empty cart
     */
    public function test_can_get_empty_cart()
    {
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}"
        ])->getJson('/api/carts');

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'data']);
    }

    /**
     * Test: Cannot access cart without authentication
     */
    public function test_cannot_access_cart_without_authentication()
    {
        $response = $this->getJson('/api/carts');

        $response->assertStatus(401);
    }

    /**
     * Test: Add item to cart
     */
    public function test_can_add_item_to_cart()
    {
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}"
        ])->postJson('/api/carts', [
            'product_id' => $this->product->id,
            'quantity' => 2
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'message', 'data']);

        $this->assertDatabaseHas('carts', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 2
        ]);
    }

    /**
     * Test: Adding product that doesn't exist fails
     */
    public function test_adding_non_existent_product_fails()
    {
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}"
        ])->postJson('/api/carts', [
            'product_id' => 99999,
            'quantity' => 1
        ]);

        $response->assertStatus(404);
    }

    /**
     * Test: Update cart quantity
     */
    public function test_can_update_cart_quantity()
    {
        Cart::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 1
        ]);

        $cart = Cart::where('user_id', $this->user->id)->first();

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}"
        ])->putJson("/api/carts/{$cart->id}", [
            'quantity' => 5
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('carts', [
            'id' => $cart->id,
            'quantity' => 5
        ]);
    }

    /**
     * Test: Remove item from cart
     */
    public function test_can_remove_item_from_cart()
    {
        $cart = Cart::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 1
        ]);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}"
        ])->deleteJson("/api/carts/{$cart->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('carts', ['id' => $cart->id]);
    }

    /**
     * Test: Get cart count
     */
    public function test_can_get_cart_count()
    {
        Cart::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 3
        ]);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}"
        ])->getJson('/api/carts/count');

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'data']);
    }

    /**
     * Test: Clear cart
     */
    public function test_can_clear_cart()
    {
        Cart::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 1
        ]);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}"
        ])->postJson('/api/carts/reset');

        $response->assertStatus(200);

        $this->assertEquals(0, Cart::where('user_id', $this->user->id)->count());
    }
}
