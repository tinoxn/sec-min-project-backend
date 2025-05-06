<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_order_with_items()
    {
        $product = Product::factory()->create();

        $payload = [
            'order_number' => 'ORD-123',
            'customer_name' => 'John Doe',
            'customer_code' => 'CUST001',
            'status' => 'pending',
            'order_date' => now()->format('Y-m-d'),
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'price' => $product->price,
                    'total' => $product->price * 2
                ]
            ]
        ];

        $response = $this->postJson('/api/orders', $payload);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'order_number',
                'customer_name',
                'customer_code',
                'status',
                'order_date',
                'items' => [
                    '*' => [
                        'id',
                        'product_id',
                        'quantity',
                        'price',
                        'total',
                        'product' => [
                            'id',
                            'name',
                            // other product fields
                        ]
                    ]
                ]
            ]);

        // Verify database state
        $this->assertDatabaseHas('orders', [
            'order_number' => 'ORD-123',
            'customer_name' => 'John Doe'
        ]);

        $orderId = $response->json('id');
        $this->assertDatabaseHas('order_items', [
            'order_id' => $orderId,
            'product_id' => $product->id,
            'quantity' => 2
        ]);
    }

    public function test_can_update_order()
    {
        $order = Order::factory()->create(['status' => 'pending']);

        $response = $this->putJson("/api/orders/{$order->id}", [
            'status' => 'shipped'
        ]);

        $response->assertOk()
            ->assertJson(['status' => 'shipped']);

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'shipped']);
    }

    public function test_can_delete_order()
    {
        $order = Order::factory()->create();

        $response = $this->deleteJson("/api/orders/{$order->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }

    public function test_can_list_orders_with_filters()
    {
        Order::factory()->count(3)->create(['status' => 'pending']);
        Order::factory()->count(2)->create(['status' => 'shipped']);

        $response = $this->getJson('/api/orders?status=shipped');

        $response->assertOk()
            ->assertJsonCount(2);
    }
}
