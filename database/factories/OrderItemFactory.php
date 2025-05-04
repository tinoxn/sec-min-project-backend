<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        $quantity = $this->faker->numberBetween(1, 5);
        $price    = $this->faker->randomFloat(2, 5, 200);

        return [
            'order_id' => Order::factory(),
            'product'  => $this->faker->word(),
            'quantity' => $quantity,
            'price'    => $price,
            'total'    => $quantity * $price,
        ];
    }
}