<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderItemApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_order_item()
    {
        $order = Order::factory()->create();
        $product = Product::factory()->create(['name' => 'Gadget', 'price' => 15.00]);

        $payload = [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ];

        $response = $this->postJson('/api/order-items', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => 2,
                'price' => 15,
                'total' => 30,
            ])
            ->assertJsonStructure([
                'id',
                'order_id',
                'product_id',
                'quantity',
                'price',
                'total',
                'created_at',
                'updated_at',
                'product' => [
                    'id',
                    'name',
                    'price'
                ]
            ]);

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'price' => 15.00,
            'total' => 30.00,
        ]);
    }

    public function test_can_update_order_item()
    {
        $order = Order::factory()->create();
        $product = Product::factory()->create(['price' => 10.00]);
        $item = OrderItem::factory()
            ->for($order)
            ->for($product)
            ->create(['quantity' => 1, 'price' => 10.00, 'total' => 10.00]);

        $payload = [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 3
        ];

        $response = $this->putJson("/api/order-items/{$item->id}", $payload);

        $response->assertOk()
            ->assertJson([
                'quantity' => 3,
                'price' => 10.00,
                'total' => 30.00,
                'product' => [
                    'id' => $product->id
                ]
            ]);

        $this->assertDatabaseHas('order_items', [
            'id' => $item->id,
            'quantity' => 3,
            'total' => 30.00,
        ]);
    }

    public function test_can_get_order_item()
    {
        $order = Order::factory()->create();
        $product = Product::factory()->create(['name' => 'Test Product']);
        $item = OrderItem::factory()
            ->for($order)
            ->for($product)
            ->create();

        $response = $this->getJson("/api/order-items/{$item->id}");

        $response->assertOk()
            ->assertJson([
                'id' => $item->id,
                'product_id' => $product->id,
            ])
            ->assertJsonStructure([
                'id',
                'order_id',
                'product_id',
                'quantity',
                'price',
                'total',
                'created_at',
                'updated_at',
                'product' => [
                    'id',
                    'name',
                    'price'
                ]
            ]);
    }

    public function test_can_delete_order_item()
    {
        $order = Order::factory()->create();
        $product = Product::factory()->create();
        $item = OrderItem::factory()
            ->for($order)
            ->for($product)
            ->create();

        $response = $this->deleteJson("/api/order-items/{$item->id}");

        $response->assertNoContent();
        $this->assertDatabaseMissing('order_items', ['id' => $item->id]);
    }
}
