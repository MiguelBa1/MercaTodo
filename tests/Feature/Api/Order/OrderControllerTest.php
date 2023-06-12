<?php

namespace Tests\Feature\Api\Order;

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

    public function testStoreANewOrderAndRedirectsToPlaceToPay()
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
            config('placetopay.url') . '/*' => Http::response($mockResponse)
        ]);

        $this->mock(CartService::class, function ($mock) {
            $mock->shouldReceive('getProductsWithDetails')->andReturn([
                $this->product
            ]);
            $mock->shouldReceive('clear');
        });

        $response = $this->post(route('api.order.store'));

        $response->assertStatus(201);

        $response->assertJson([
            'redirect_url' => 'https://test.placetopay.com/',
        ]);
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
            'product_name' => $this->product->getAttribute('name'),
            'product_price' => $this->product->getAttribute('price'),
            'quantity' => 1,
        ]);
    }

    public function testStoreWithEmptyCart(): void
    {
        $this->mock(CartService::class, function ($mock) {
            $mock->shouldReceive('getProductsWithDetails')->andReturn([]);
        });

        $response = $this->post(route('api.order.store'));

        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'Cart is empty',
        ]);
    }

    public function testStoreWithUnavailableProducts(): void
    {
        $this->mock(CartService::class, function ($mock) {
            $mock->shouldReceive('getProductsWithDetails')->andReturn([
                $this->product->id => $this->product
            ]);
        });

        $this->product->stock = 0;

        $response = $this->post(route('api.order.store'));

        $response->assertStatus(400);
        $response->assertJson([
            'message' => 'Product ' . $this->product->name . ' is not available, please remove it from cart',
        ]);
    }

    public function testProductIsDecrementedOnStoreOrder(): void
    {
        $this->mock(CartService::class, function ($mock) {
            $mock->shouldReceive('getProductsWithDetails')->andReturn([
                $this->product
            ]);
            $mock->shouldReceive('clear');
        });

        $this->post(route('api.order.store'));

        $this->assertDatabaseHas('products', [
            'id' => $this->product->getKey(),
            'stock' => $this->product->stock - 1,
        ]);
    }
}
