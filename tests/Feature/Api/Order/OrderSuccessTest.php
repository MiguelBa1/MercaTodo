<?php

namespace Tests\Feature\Api\Order;

use App\Services\Cart\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\Feature\Utilities\ProductTestCase;

class OrderSuccessTest extends ProductTestCase
{
    use RefreshDatabase;

    public const QUANTITY = 1;
    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs($this->customerUser);
        (new CartService())->addProduct(
            $this->customerUser->getKey(),
            $this->product->getKey(),
            self::QUANTITY
        );
    }

    public function testStoreANewOrder(): void
    {
        $mockResponse = $this->getMockResponse();
        Http::fake([
            config('placetopay.url') . '/*' => Http::response($mockResponse)
        ]);

        $response = $this->post(route('api.order.store'));

        $response->assertStatus(201);
        $this->assertOrderCreated();
    }

    private function getMockResponse(): array
    {
        return [
            "status" => [
                "status" => "APPROVED",
                "reason" => "00",
                "message" => "The transaction has been approved",
                "date" => "2021-11-30T15:08:27-05:00",
            ],
            "requestId" => 1,
            "processUrl" => "https://test.placetopay.com/",
        ];
    }

    private function assertOrderCreated(): void
    {
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_details', 1);
        $this->assertDatabaseHas('orders', [
            'user_id' => $this->customerUser->getKey(),
            'total' => $this->product->price,
            'request_id' => 1,
            'process_url' => 'https://test.placetopay.com/',
        ]);
        $this->assertDatabaseHas('order_details', [
            'product_id' => $this->product->getKey(),
            'product_name' => $this->product->name,
            'product_price' => $this->product->price,
            'quantity' => 1,
        ]);
    }

    public function testStoreRedirectsToPlaceToPay(): void
    {
        $mockResponse = $this->getMockResponse();
        Http::fake([
            config('placetopay.url') . '/*' => Http::response($mockResponse)
        ]);

        $response = $this->post(route('api.order.store'));

        $response->assertStatus(201);
        $response->assertJson([
            'redirect_url' => 'https://test.placetopay.com/',
        ]);
    }

    public function testProductIsDecrementedOnStoreOrder(): void
    {
        $this->product->stock = 2;
        $this->product->save();

        $response = $this->post(route('api.order.store'));

        $firstStock = $this->product->stock;
        $this->product->refresh();

        $response->assertStatus(201);
        $this->assertDatabaseHas('products', [
            'id' => $this->product->getKey(),
            'stock' => $firstStock - self::QUANTITY,
        ]);
    }
}
