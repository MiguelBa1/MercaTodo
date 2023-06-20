<?php

namespace Tests\Feature\Console;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Tests\Feature\Utilities\UserTestCase;

class CheckPaymentSessionTest extends UserTestCase
{
    public function testHandlePendingOrders(): void
    {
        /** @var Order[] $orders */
        $orders = Order::factory()->count(3)->create([
            'status' => OrderStatusEnum::PENDING,
            'user_id' => $this->customerUser->id,
        ]);

        Http::fake([
            config('placetopay.url') . '/api/session/*' => Http::response([
                "status" => [
                    "status" => "APPROVED",
                    "reason" => "00",
                    "message" => "La petición ha sido aprobada exitosamente",
                    "date" => "2021-11-30T15:08:27-05:00",
                ],
            ])
        ]);

        Artisan::call('app:check-payment-session');

        foreach ($orders as $order) {
            $this->assertDatabaseHas('orders', [
                'id' => $order->id,
                'status' => OrderStatusEnum::COMPLETED,
            ]);
        }
    }
}
