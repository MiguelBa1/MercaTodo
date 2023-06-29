<?php

namespace Tests\Feature\Api\Order;

use App\Services\Cart\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
            'message' => 'The cart is empty, please add products to the cart.',
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
            'message' => 'The product ' . $this->product->name . ' is no longer available, please remove it from the cart.',
        ]);
    }
}
