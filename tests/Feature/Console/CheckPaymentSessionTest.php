<?php

namespace Tests\Feature\Console;

use App\Enums\OrderStatusEnum;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Tests\Feature\Utilities\UserTestCase;

class CheckPaymentSessionTest extends UserTestCase
{
    public function testHandlePendingOrders(): void
    {
        Category::factory()->count(5)->create();
        Brand::factory()->count(5)->create();
        Product::factory()->count(5)->create();

        /** @var Order[] $orders */
        $orders = Order::factory()->count(3)->create([
            'status' => OrderStatusEnum::PENDING,
            'user_id' => $this->customerUser->id,
        ]);

        Http::fake([
            config('placetopay.url') . '/*' => Http::response([
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
