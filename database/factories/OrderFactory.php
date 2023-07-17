<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderDetail;
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
        /** @var User $user */
        $user = User::query()->inRandomOrder()->first();

        return [
            'reference' => $this->faker->unique()->randomNumber(),
            'user_id' => $user->id,
            'total' => 0,
            'request_id' => $this->faker->randomNumber(),
            'process_url' => $this->faker->url,
            'status' => $this->faker->randomElement(array_column(OrderStatusEnum::cases(), 'value')),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function configure(): self
    {
        return $this->afterCreating(function (Order $order) {
            $orderDetails = OrderDetail::factory()->count(rand(1, 3))->make([
                'order_id' => $order->id,
                'created_at' => $order->created_at,
            ]);

            $orderDetails->each(function (OrderDetail $orderDetail) use ($order) {
                $orderDetail->save();

                $order->total += $orderDetail->product_price * $orderDetail->quantity;
            });

            $order->save();
        });
    }
}
