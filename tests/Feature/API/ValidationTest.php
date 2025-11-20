<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Email validation in registration
     */
    public function test_invalid_email_format_rejected()
    {
        $response = $this->postJson('/api/signup', [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test: Weak password rejected
     */
    public function test_weak_password_rejected()
    {
        $response = $this->postJson('/api/signup', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => '123',
            'password_confirmation' => '123'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['password']);
    }

    /**
     * Test: Password confirmation must match
     */
    public function test_password_confirmation_must_match()
    {
        $response = $this->postJson('/api/signup', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'DifferentPassword123!'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['password']);
    }

    /**
     * Test: Required fields validation
     */
    public function test_required_fields_validation()
    {
        $response = $this->postJson('/api/signup', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    /**
     * Test: Email must be unique
     */
    public function test_email_must_be_unique()
    {
        \App\Models\User::factory()->create([
            'email' => 'duplicate@example.com'
        ]);

        $response = $this->postJson('/api/signup', [
            'name' => 'Another User',
            'email' => 'duplicate@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test: Product quantity must be positive integer
     */
    public function test_product_quantity_validation()
    {
        $user = \App\Models\User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        $product = \App\Models\Product::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->postJson('/api/carts', [
            'product_id' => $product->id,
            'quantity' => -5
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['quantity']);
    }

    /**
     * Test: Shipping address required for order
     */
    public function test_shipping_address_required_for_order()
    {
        $user = \App\Models\User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->postJson('/api/orders', [
            'city' => 'New York',
            'state' => 'NY'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['shipping_address']);
    }

    /**
     * Test: Invalid payment gateway rejected
     */
    public function test_invalid_payment_gateway_rejected()
    {
        $user = \App\Models\User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;
        $order = \App\Models\Order::factory()->create(['user_id' => $user->id]);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->postJson('/api/payments', [
            'order_id' => $order->id,
            'gateway' => 'invalid_gateway',
            'token' => 'tok_visa'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['gateway']);
    }
}
