<?php

namespace Database\Factories;

use App\Models\OrderDetail;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\Product;

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
        /** @var Product $product */
        $product = Product::query()->inRandomOrder()->first();

        /** @var Order $order */
        $order = Order::query()->inRandomOrder()->first();

        return [
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_price' => $product->price,
            'quantity' => $this->faker->numberBetween(1, 10),
            'order_id' => $order->id,
        ];
    }
}
