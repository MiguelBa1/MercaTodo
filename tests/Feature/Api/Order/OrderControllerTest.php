<?php

namespace Tests\Feature\Api\Order;

use App\Enums\OrderStatusEnum;
use App\Services\CartService;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilities\ProductTestCase;

class OrderControllerTest extends ProductTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs($this->customerUser);
    }

    public function testStore()
    {
        $this->mock(CartService::class, function ($mock) {
            $mock->shouldReceive('getProducts')
                ->once()
                ->andReturn([
                    $this->product->getAttribute('id') => 1
                ]);
            $mock->shouldReceive('getTotal')
                ->once()
                ->andReturn(100);
            $mock->shouldReceive('clear')
                ->once();
        });

        $response = $this->postJson(route('api.order.store'));

        $response->assertStatus(201);

        $response->assertJson([
            'message' => 'Order created successfully'
        ]);

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_details', 1);
        $this->assertDatabaseHas('orders', [
            'user_id' => $this->customerUser->getKey(),
            'total' => 100,
        ]);
    }

    public function testIndex()
    {
        $order = Order::factory()->create([
            'user_id' => $this->customerUser->getAttribute('id'),
            'total' => 100,
            'status' => OrderStatusEnum::PENDING->value
        ]);

        $orderDetail = OrderDetail::factory()->create([
            'order_id' => $order->getAttribute('id'),
            'product_id' => $this->product->getAttribute('id'),
            'product_name' => $this->product->getAttribute('name'),
            'product_price' => 100,
            'quantity' => 1,
        ]);

        $response = $this->getJson(route('api.order.index'));

        $response->assertStatus(200);

        $response->assertJson([
            'orders' => [
                [
                    'id' => $order->getAttribute('id'),
                    'total' => $order->getAttribute('total'),
                    'status' => OrderStatusEnum::PENDING->value,
                    'order_details' => [
                        [
                            'id' => $orderDetail->getAttribute('id'),
                            'product_id' => $this->product->getAttribute('id'),
                            'product_name' => $this->product->getAttribute('name'),
                            'product_price' => 100.00,
                            'quantity' => 1,
                        ]
                    ]
                ]
            ]
        ]);
    }
}
