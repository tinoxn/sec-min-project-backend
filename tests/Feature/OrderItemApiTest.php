<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderItemApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_order_item()
    {
        $order = Order::factory()->create();

        $payload = [
            'order_id' => $order->id,
            'product'  => 'Gadget',
            'quantity' => 2,
            'price'    => 15.00,
            'total'    => 30.00,
        ];

        $response = $this->postJson('/api/order-items', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'order_id' => $order->id,
                'product'  => 'Gadget',
                'quantity' => 2,
                'price'    => 15.00,
                'total'    => 30.00,
            ]);

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product'  => 'Gadget',
        ]);
    }

    public function test_can_get_order_item()
    {
        $order = Order::factory()->create();
        $item = OrderItem::factory()->for($order)->create();

        $response = $this->getJson("/api/order-items/{$item->id}");

        $response->assertOk()
            ->assertJsonFragment([
                'id'       => $item->id,
                'product'  => $item->product,
            ]);
    }

    public function test_can_update_order_item()
    {
        $order = Order::factory()->create();
        $item = OrderItem::factory()->for($order)->create(['quantity' => 1, 'total' => 5]);

        $payload = [
            'order_id' => $order->id,
            'product' => $item->product,
            'price' => $item->price,
            'quantity' => 4,
            'total' => 20
        ];

        $response = $this->putJson("/api/order-items/{$item->id}", $payload);

        $response->assertOk()
            ->assertJsonFragment(['quantity' => 4, 'total' => 20]);

        $this->assertDatabaseHas('order_items', [
            'id'       => $item->id,
            'quantity' => 4,
        ]);
    }

    public function test_can_delete_order_item()
    {
        $order = Order::factory()->create();
        $item = OrderItem::factory()->for($order)->create();

        $response = $this->deleteJson("/api/order-items/{$item->id}");

        $response->assertNoContent();

        $this->assertDatabaseMissing('order_items', ['id' => $item->id]);
    }
}