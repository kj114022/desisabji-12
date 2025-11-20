<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Cart;

class OrderTest extends TestCase
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
     * Test: User cannot access orders without authentication
     */
    public function test_cannot_access_orders_without_authentication()
    {
        $response = $this->getJson('/api/orders');

        $response->assertStatus(401);
    }

    /**
     * Test: Get user's orders list
     */
    public function test_can_get_user_orders()
    {
        Order::factory()->create(['user_id' => $this->user->id]);
        Order::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}"
        ])->getJson('/api/orders');

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'data']);
    }

    /**
     * Test: Get single order details
     */
    public function test_can_get_single_order()
    {
        $order = Order::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}"
        ])->getJson("/api/orders/{$order->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'data']);
    }

    /**
     * Test: Cannot access other user's order
     */
    public function test_cannot_access_other_user_order()
    {
        $otherUser = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}"
        ])->getJson("/api/orders/{$order->id}");

        $response->assertStatus(403);
    }

    /**
     * Test: Create order from cart
     */
    public function test_can_create_order_from_cart()
    {
        Cart::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'quantity' => 2
        ]);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}"
        ])->postJson('/api/orders', [
            'shipping_address' => '123 Main St',
            'city' => 'New York',
            'state' => 'NY',
            'zip_code' => '10001',
            'country' => 'USA'
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['success', 'message', 'data']);

        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user->id
        ]);
    }

    /**
     * Test: Cannot create order with empty cart
     */
    public function test_cannot_create_order_with_empty_cart()
    {
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}"
        ])->postJson('/api/orders', [
            'shipping_address' => '123 Main St',
            'city' => 'New York',
            'state' => 'NY',
            'zip_code' => '10001',
            'country' => 'USA'
        ]);

        $response->assertStatus(400);
    }

    /**
     * Test: Cancel order
     */
    public function test_can_cancel_order()
    {
        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'pending'
        ]);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}"
        ])->postJson("/api/orders/{$order->id}/cancel");

        $response->assertStatus(200);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'cancelled'
        ]);
    }

    /**
     * Test: Get non-existent order returns 404
     */
    public function test_get_non_existent_order_returns_404()
    {
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}"
        ])->getJson('/api/orders/99999');

        $response->assertStatus(404);
    }
}
