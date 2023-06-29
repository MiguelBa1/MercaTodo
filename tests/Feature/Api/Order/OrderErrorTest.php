<?php

namespace Tests\Feature\Api\Order;

use App\Services\Cart\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\Feature\Utilities\ProductTestCase;

class OrderErrorTest extends ProductTestCase
{
    use RefreshDatabase;

    public const QUANTITY = 1;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs($this->customerUser);
    }

    public function testStoreWithEmptyCartThrowsCartValidationException(): void
    {
        $response = $this->post(route('api.order.store'));

        $response->assertStatus(422);
        $response->assertJson([
            'message' => __('validation.custom.cart.empty'),
        ]);
    }

    public function testStoreWithUnavailableProducts(): void
    {
        (new CartService())->addProduct(
            $this->customerUser->getKey(),
            $this->product->getKey(),
            self::QUANTITY
        );

        $this->product->status = 0;
        $this->product->stock = 0;
        $this->product->save();

        $response = $this->post(route('api.order.store'));

        $response->assertStatus(422);
        $response->assertJson([
            'message' => __('validation.custom.product.unavailable', [
                'product_name' => $this->product->name,
            ]),
        ]);
    }

    public function testPaymentAuthenticationError(): void
    {
        (new CartService())->addProduct(
            $this->customerUser->getKey(),
            $this->product->getKey(),
            self::QUANTITY
        );

        $mockResponse = $this->getMockAuthenticationError();
        Http::fake([
            config('placetopay.url') . '/*' => Http::response($mockResponse, 401)
        ]);

        $response = $this->post(route('api.order.store'));

        $response->assertStatus(503);
        $response->assertJson(['message' => __('validation.custom.payment.authentication_error')]);
    }

    public function testPaymentSessionError(): void
    {
        (new CartService())->addProduct(
            $this->customerUser->getKey(),
            $this->product->getKey(),
            self::QUANTITY
        );

        $mockResponse = $this->getMockSessionError();
        Http::fake([
            config('placetopay.url') . '/*' => Http::response($mockResponse, 402)
        ]);

        $response = $this->post(route('api.order.store'));

        $response->assertStatus(503);
        $response->assertJson(['message' => __('validation.custom.payment.session_error')]);
    }

    private function getMockAuthenticationError(): array
    {
        return [
            'status' => [
                'reason' => '401',
                'message' => 'Authentication failed',
            ]
        ];
    }

    private function getMockSessionError(): array
    {
        return [
            'status' => [
                'reason' => '402',
                'message' => 'Session error',
            ]
        ];
    }
}
