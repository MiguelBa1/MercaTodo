<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\OrderStatusEnum;
use App\Models\User;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reference' => $this->faker->unique()->randomNumber(),
            'user_id' => User::factory(),
            'total' => $this->faker->randomFloat(2, 1, 1000),
            'status' => $this->faker->randomElement(array_column(OrderStatusEnum::cases(), 'value')),
        ];
    }
}
