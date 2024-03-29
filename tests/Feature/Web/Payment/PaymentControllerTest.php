<?php

namespace Tests\Feature\Web\Payment;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Inertia\Testing\AssertableInertia;
use Tests\Feature\Utilities\ProductTestCase;

class PaymentControllerTest extends ProductTestCase
{
    use RefreshDatabase;

    protected Order $pendingOrder;
    protected Order $completedOrder;

    protected Order $rejectedOrder;

    public function setUp(): void
    {
        parent::setUp();

        $this->pendingOrder = Order::factory()->create([
            'status' => OrderStatusEnum::PENDING,
            'user_id' => $this->customerUser->id,
        ]);

        $this->completedOrder = Order::factory()->create([
            'status' => OrderStatusEnum::COMPLETED,
            'user_id' => $this->customerUser->id,
        ]);

        $this->rejectedOrder = Order::factory()->create([
            'status' => OrderStatusEnum::REJECTED,
            'user_id' => $this->customerUser->id,
            "process_url" => "https://test.placetopay.com/",
        ]);
    }

    public function testHandleRedirectWithPendingOrderIsApproved(): void
    {
        $mockResponse = [
            "requestId" => $this->pendingOrder->request_id,
            "status" => [
                "status" => "APPROVED",
                "reason" => "00",
                "message" => "La petición ha sido aprobada exitosamente",
                "date" => "2021-11-30T15:08:27-05:00",
            ],
        ];

        Http::fake([config('placetopay.url') . '/*' => $mockResponse,]);

        $response = $this->actingAs($this->customerUser)->get(route('payment.result', $this->pendingOrder->id));

        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page) => $page
                ->component('Payment/Result', function (AssertableInertia $page) {
                    $page->where('order.status', OrderStatusEnum::COMPLETED)
                        ->where('order.reference', $this->pendingOrder->reference)
                        ->where('order.total', $this->pendingOrder->total)
                        ->where('order.created_at', $this->pendingOrder->created_at->format('Y-m-d H:i:s'));
                })
        );

        $this->assertDatabaseHas('orders', [
            'id' => $this->pendingOrder->id,
            'status' => OrderStatusEnum::COMPLETED,
        ]);
    }

    public function testHandleRedirectWithPendingOrderIsRejected(): void
    {
        $mockResponse = [
            "requestId" => $this->pendingOrder->request_id,
            "status" => [
                "status" => "REJECTED",
                "reason" => "00",
                "message" => "La petición ha sido rechazada",
                "date" => "2021-11-30T15:08:27-05:00",
            ],
        ];

        Http::fake([config('placetopay.url') . '/*' => $mockResponse,]);

        $response = $this->actingAs($this->customerUser)->get(route('payment.result', $this->pendingOrder->id));

        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page) => $page
                ->component('Payment/Result', function (AssertableInertia $page) {
                    $page->where('order.status', OrderStatusEnum::REJECTED)
                        ->where('order.reference', $this->pendingOrder->reference)
                        ->where('order.total', $this->pendingOrder->total)
                        ->where('order.created_at', $this->pendingOrder->created_at->format('Y-m-d H:i:s'));
                })
        );

        $this->assertDatabaseHas('orders', [
            'id' => $this->pendingOrder->id,
            'status' => OrderStatusEnum::REJECTED,
        ]);
    }

    public function testHandleRedirectWithPendingOrderStillPending(): void
    {
        $mockResponse = [
            "requestId" => $this->pendingOrder->request_id,
            "status" => [
                "status" => "PENDING",
                "reason" => "00",
                "message" => "La petición ha sido rechazada",
                "date" => "2021-11-30T15:08:27-05:00",
            ],
        ];

        Http::fake([config('placetopay.url') .'/*' => $mockResponse,]);

        $response = $this->actingAs($this->customerUser)->get(route('payment.result', $this->pendingOrder->id));

        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page) => $page
                ->component('Payment/Result', function (AssertableInertia $page) {
                    $page->where('order.status', OrderStatusEnum::PENDING)
                        ->where('order.reference', $this->pendingOrder->reference)
                        ->where('order.total', $this->pendingOrder->total)
                        ->where('order.created_at', $this->pendingOrder->created_at->format('Y-m-d H:i:s'));
                })
        );

        $this->assertDatabaseHas('orders', [
            'id' => $this->pendingOrder->id,
            'status' => OrderStatusEnum::PENDING,
        ]);
    }

    public function testHandleRedirectWithPendingOrderFailsRequest(): void
    {
        Http::fake([config('placetopay.url') . '/*' => Http::response([], 500)]);

        $response = $this->actingAs($this->customerUser)->get(route('payment.result', $this->pendingOrder->id));

        $response->assertServiceUnavailable();
    }

    public function testHandleRedirectWithNonPendingOrder(): void
    {
        $response = $this->actingAs($this->customerUser)->get(route('payment.result', $this->completedOrder->id));

        $response->assertFound();
        $response->assertRedirect(route('home'));
    }

    public function testHandleCanceledWithNotPendingOrder(): void
    {
        $response = $this->actingAs($this->customerUser)->get(route('payment.canceled', $this->completedOrder->id));

        $response->assertFound();
        $response->assertRedirect(route('home'));
    }

    public function testHandleCanceledWithPendingOrder(): void
    {
        $mockResponse = [
            "requestId" => $this->pendingOrder->request_id,
            "status" => [
                "status" => "REJECTED",
                "reason" => "00",
                "message" => "La petición ha sido rechazada",
                "date" => "2021-11-30T15:08:27-05:00",
            ],
        ];

        Http::fake([config('placetopay.url') . '/*' => $mockResponse,]);
        $response = $this->actingAs($this->customerUser)->get(route('payment.canceled', $this->pendingOrder->id));

        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page) => $page
                ->component('Payment/Canceled')
        );

        $this->assertDatabaseHas('orders', [
            'id' => $this->pendingOrder->id,
            'status' => OrderStatusEnum::REJECTED,
        ]);
    }

    public function testRetryOrderWithNotPendingOrder(): void
    {
        $response = $this->actingAs($this->customerUser)->get(route('payment.retry', $this->completedOrder->id));

        $response->assertStatus(302);
        $response->assertRedirect(route('home'));
    }

    public function testRetryOrderWithPendingOrder(): void
    {
        $response = $this->actingAs($this->customerUser)->get(route('payment.retry', $this->pendingOrder->id));

        $response->assertCreated();
        $response->assertJson([
            'redirect_url' => $this->pendingOrder->process_url
        ]);
    }

    public function testRetryOrderWithRejectedOrder(): void
    {
        $mockResponse = [
            "status" => [
                "status" => "APPROVED",
                "reason" => "00",
                "message" => "La petición ha sido aprobada exitosamente",
                "date" => "2021-11-30T15:08:27-05:00",
            ],
            "requestId" => 1,
            "processUrl" => "https://test.placetopay.com/",
        ];

        Http::fake([
            config('placetopay.url') . '/*' => Http::response($mockResponse)]);

        // Restore stock manually to avoid the exception
        foreach ($this->rejectedOrder->orderDetails as $orderDetail) {
            $this->product->stock += $orderDetail->quantity;
            $this->product->save();
        }

        $response = $this->actingAs($this->customerUser)->get(route('payment.retry', $this->rejectedOrder->id));

        $response->assertCreated();
        $response->assertJson([
            'redirect_url' => $this->rejectedOrder->process_url
        ]);
    }

    public function testProductStockIsRestoredInRejectedOrder(): void
    {
        $mockResponse = [
            "requestId" => $this->pendingOrder->request_id,
            "status" => [
                "status" => "REJECTED",
                "reason" => "00",
                "message" => "La petición ha sido rechazada",
                "date" => "2021-11-30T15:08:27-05:00",
            ],
        ];

        Http::fake([config('placetopay.url') . '/*' => $mockResponse,]);

        $this->product->stock = 1;
        $this->product->save();

        $this->assertDatabaseHas('products', [
            'id' => $this->product->id,
            'stock' => 1,
        ]);

        $response = $this->actingAs($this->customerUser)->get(route('payment.result', $this->pendingOrder->id));

        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page) => $page
                ->component('Payment/Result', function (AssertableInertia $page) {
                    $page->where('order.status', OrderStatusEnum::REJECTED)
                        ->where('order.reference', $this->pendingOrder->reference)
                        ->where('order.total', $this->pendingOrder->total)
                        ->where('order.created_at', $this->pendingOrder->created_at->format('Y-m-d H:i:s'));
                })
        );

        $restoredProductQuantity = 0;

        foreach ($this->pendingOrder->orderDetails as $orderDetail) {
            if ($orderDetail->product_id === $this->product->id) {
                $restoredProductQuantity += $orderDetail->quantity;
            }
        }

        $this->assertDatabaseHas('products', [
            'id' => $this->product->id,
            'stock' => 1 + $restoredProductQuantity,
        ]);
    }
}
