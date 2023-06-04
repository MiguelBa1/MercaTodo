<?php

namespace Database\Factories;

use App\Models\OrderDetail;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;

/**
 * @extends Factory<OrderDetail>
 */
class OrderDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => $this->faker->unique()->randomNumber(),
            'product_name' => $this->faker->name(),
            'product_price' => $this->faker->randomFloat(2, 1, 1000),
            'quantity' => $this->faker->randomNumber(),
            'order_id' => Order::factory(),
        ];
    }
}
