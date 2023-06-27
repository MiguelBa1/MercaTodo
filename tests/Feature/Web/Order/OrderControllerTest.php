<?php

namespace Tests\Feature\Web\Order;

use App\Models\Order;
use App\Models\OrderDetail;
use Inertia\Testing\AssertableInertia;
use Tests\Feature\Utilities\ProductTestCase;

class OrderControllerTest extends ProductTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testOrderRendersCorrectView(): void
    {
        /** @var Order $order */
        $order = Order::factory()->create(['user_id' => $this->customerUser->id]);
        OrderDetail::factory()->count(3)->create(['order_id' => $order->id]);

        $response = $this->actingAs($this->customerUser)->get(route('order.index', $order->id));

        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page) => $page
            ->component('Orders/Index')
            ->has('orders')
        );
    }
}
