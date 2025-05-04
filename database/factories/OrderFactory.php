<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    // The name of the factory’s corresponding model.
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'customer_name' => $this->faker->name(),
            'status'        => $this->faker->randomElement(['pending', 'shipped', 'completed', 'cancelled']),
            'order_date'    => $this->faker->date(),
            // total_price will be computed in  repository/service,
            // but we can set a placeholder or leave as zero:
            'total_price'   => 0,
        ];
    }

    /**
     * Attach a given number of items to the order.
     *
     * @param  int  $count
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withItems(int $count = 1): Factory
    {
        return $this->afterCreating(function (Order $order) use ($count) {
            $items = \App\Models\OrderItem::factory()->count($count)->make()->toArray();

            // Associate each item with this order and compute its total
            foreach ($items as $itemData) {
                $itemData['order_id'] = $order->id;
                $itemData['total']    = $itemData['price'] * $itemData['quantity'];
                $order->items()->create($itemData);
            }

            // Recompute total_price on the order
            $order->total_price = $order->items->sum('total');
            $order->save();
        });
    }
}