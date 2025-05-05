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
        $product = Product::factory()->create(['name' => 'Book', 'price' => 10.00]);

        $payload = [
            'customer_name' => 'Alice',
            'customer_code' => 'Al12345',
            'order_number' => 'OAl12345',
            'status' => 'pending',
            'order_date' => now()->toDateString(),
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'price' => 10.00,
                    'total' => 20.00
                ]
            ]
        ];

        $response = $this->postJson('/api/orders', $payload);
        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'order_number',
                'items' => [
                    '*' => [
                        'id',
                        'product_id', // or 'product' depending on your API
                        'quantity',
                        'price',
                        'total'
                    ]
                ]
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
