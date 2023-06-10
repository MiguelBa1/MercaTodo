<?php

namespace Tests\Feature\Api\Order;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\Feature\Utilities\ProductTestCase;

class OrderControllerTest extends ProductTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs($this->customerUser);
        $this->product['quantity'] = 1;
    }

    public function testStoreAnOrder()
    {
        $mockResponse = [
            "status" => [
                "status" => "APPROVED",
                "reason" => "00",
                "message" => "La petición ha sido aprobada exitosamente",
                "date" => "2021-11-30T15:08:27-05:00",
            ],
            "requestId" => 1,
            "processUrl" => "https://checkout-co.placetopay.com/session/1/cc9b8690b1f7228c78b759ce27d7e80a",
        ];

        Http::fake([
            config('placetopay.url') . '/*' => Http::response($mockResponse)]);

        $this->mock(CartService::class, function ($mock) {
            $mock->shouldReceive('getProductsWithDetails')->andReturn([
                $this->product
            ]);
            $mock->shouldReceive('clear');
        });

        $response = $this->post(route('api.order.store'));

        $response->assertStatus(201);

        $response->assertJson([
            'redirect_url' => 'https://checkout-co.placetopay.com/session/1/cc9b8690b1f7228c78b759ce27d7e80a',
        ]);
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_details', 1);
        $this->assertDatabaseHas('orders', [
            'user_id' => $this->customerUser->getKey(),
            'total' => $this->product->price,
            'request_id' => 1,
            'process_url' => 'https://checkout-co.placetopay.com/session/1/cc9b8690b1f7228c78b759ce27d7e80a',
        ]);
        $this->assertDatabaseHas('order_details', [
            'product_id' => $this->product->getKey(),
            'product_name' => $this->product->getAttribute('name'),
            'product_price' => $this->product->getAttribute('price'),
            'quantity' => 1,
        ]);
    }

//    public function testIndexOrders()
//    {
//        $order = Order::factory()->create([
//            'user_id' => $this->customerUser->getAttribute('id'),
//            'total' => 100,
//            'status' => OrderStatusEnum::PENDING->value
//        ]);
//
//        $orderDetail = OrderDetail::factory()->create([
//            'order_id' => $order->getAttribute('id'),
//            'product_id' => $this->product->getAttribute('id'),
//            'product_name' => $this->product->getAttribute('name'),
//            'product_price' => 100,
//            'quantity' => 1,
//        ]);
//
//        $response = $this->getJson(route('api.order.index'));
//
//        $response->assertStatus(200);
//
//        $response->assertJson([
//            'orders' => [
//                [
//                    'id' => $order->getAttribute('id'),
//                    'total' => $order->getAttribute('total'),
//                    'status' => OrderStatusEnum::PENDING->value,
//                    'order_details' => [
//                        [
//                            'id' => $orderDetail->getAttribute('id'),
//                            'product_id' => $this->product->getAttribute('id'),
//                            'product_name' => $this->product->getAttribute('name'),
//                            'product_price' => 100.00,
//                            'quantity' => 1,
//                        ]
//                    ]
//                ]
//            ]
//        ]);
//    }
}
