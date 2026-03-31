<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_all_orders()
    {
        $admin = User::factory()->admin()->create();
        $customer = User::factory()->create(['role' => 'Customer']);

        Order::factory(5)->create(['userid' => $customer->id]);

        $token = $admin->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/orders');

        $response->assertStatus(200);
    }

    public function test_customer_authenticated_to_view_orders()
    {
        $customer = User::factory()->create();
        $order = Order::factory()->create(['userid' => $customer->id]);

        $token = $customer->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson("/api/orders/{$order->id}");

        $response->assertStatus(200);
    }

    public function test_unauthenticated_cannot_view_orders()
    {
        $order = Order::factory()->create();

        $response = $this->getJson("/api/orders/{$order->id}");

        $response->assertStatus(401);
    }

    public function test_order_item_validation()
    {
        $customer = User::factory()->create();
        $token = $customer->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/order-items', []);

        $response->assertStatus(422);
    }
}

