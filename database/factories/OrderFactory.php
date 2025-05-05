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
            'order_number' => $this->faker->unique()->bothify('ORD-#####'),
            'customer_code' => $this->faker->bothify('CUST-#####'),
            'status'        => $this->faker->randomElement(['pending', 'shipped', 'completed', 'cancelled']),
            'order_date'    => $this->faker->date(),
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
            $order->save();
        });
    }
}
