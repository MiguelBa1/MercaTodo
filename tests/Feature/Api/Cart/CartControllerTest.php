<?php

namespace Tests\Feature\Api\Cart;

use Illuminate\Support\Facades\Cache;
use Tests\Feature\Utilities\ProductTestCase;

class CartControllerTest extends ProductTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->actingAs($this->customerUser);
    }

    public function testAddingProductToCart(): void
    {
        $response = $this->postJson(route('api.cart.store'), [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $cacheKey = 'user:' . $this->customerUser->id . ':cart';

        $response->assertOk();
        $cart = Cache::get($cacheKey);
        $this->assertArrayHasKey($this->product->id, $cart);
        $this->assertEquals(1, $cart[$this->product->id]);
    }

    public function testRemovingProductFromCart(): void
    {
        $response = $this->deleteJson(route('api.cart.destroy', ['product_id' => $this->product->id]));

        $cacheKey = 'user:' . $this->customerUser->id . ':cart';

        $response->assertOk();
        $cart = Cache::get($cacheKey);
        $this->assertArrayNotHasKey($this->product->id, $cart);
    }

    public function testFetchingCartContents(): void
    {
        $cacheKey = 'user:' . $this->customerUser->id . ':cart';
        Cache::put($cacheKey, [$this->product->id => 1]);

        $response = $this->getJson(route('api.cart.index'));

        $response->assertOk();
        $response->assertJsonFragment([
            $this->product->id => 1,
        ]);
    }
}
