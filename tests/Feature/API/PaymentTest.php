<?php

namespace Tests\Feature\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Payment;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $order;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('auth_token')->plainTextToken;
        $this->order = Order::factory()->create([
            'user_id' => $this->user->id,
            'total' => 199.99,
            'status' => 'pending'
        ]);
    }

    /**
     * Test: Cannot process payment without authentication
     */
    public function test_cannot_process_payment_without_authentication()
    {
        $response = $this->postJson('/api/payments', [
            'order_id' => $this->order->id,
            'gateway' => 'stripe',
            'token' => 'tok_visa'
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test: Process Stripe payment
     */
    public function test_can_process_stripe_payment()
    {
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}"
        ])->postJson('/api/payments', [
            'order_id' => $this->order->id,
            'gateway' => 'stripe',
            'token' => 'tok_visa'
        ]);

        // Note: Real test would mock Stripe API
        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'message', 'data']);
    }

    /**
     * Test: Process Razorpay payment
     */
    public function test_can_process_razorpay_payment()
    {
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}"
        ])->postJson('/api/payments', [
            'order_id' => $this->order->id,
            'gateway' => 'razorpay',
            'razorpay_payment_id' => 'pay_1234567890',
            'razorpay_order_id' => 'order_1234567890',
            'razorpay_signature' => 'signature_hash'
        ]);

        // Note: Real test would mock Razorpay API
        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'message', 'data']);
    }

    /**
     * Test: Process PayPal payment
     */
    public function test_can_process_paypal_payment()
    {
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}"
        ])->postJson('/api/payments', [
            'order_id' => $this->order->id,
            'gateway' => 'paypal',
            'paypal_order_id' => 'EC-1234567890'
        ]);

        // Note: Real test would mock PayPal API
        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'message', 'data']);
    }

    /**
     * Test: Payment fails with invalid order
     */
    public function test_payment_fails_with_invalid_order()
    {
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}"
        ])->postJson('/api/payments', [
            'order_id' => 99999,
            'gateway' => 'stripe',
            'token' => 'tok_visa'
        ]);

        $response->assertStatus(404);
    }

    /**
     * Test: Payment fails with missing gateway
     */
    public function test_payment_fails_with_missing_gateway()
    {
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}"
        ])->postJson('/api/payments', [
            'order_id' => $this->order->id,
            'token' => 'tok_visa'
        ]);

        $response->assertStatus(422);
    }

    /**
     * Test: Get payment status
     */
    public function test_can_get_payment_status()
    {
        $payment = Payment::factory()->create([
            'order_id' => $this->order->id,
            'status' => 'completed'
        ]);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}"
        ])->getJson("/api/payments/{$payment->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'data']);
    }

    /**
     * Test: Cannot access other user's payment
     */
    public function test_cannot_access_other_user_payment()
    {
        $otherUser = User::factory()->create();
        $otherOrder = Order::factory()->create(['user_id' => $otherUser->id]);
        $payment = Payment::factory()->create(['order_id' => $otherOrder->id]);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}"
        ])->getJson("/api/payments/{$payment->id}");

        $response->assertStatus(403);
    }
}
