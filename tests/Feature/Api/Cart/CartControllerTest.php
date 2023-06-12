<?php

namespace Tests\Feature\Api\Cart;

use Illuminate\Support\Facades\Cache;
use Tests\Feature\Utilities\ProductTestCase;

class CartControllerTest extends ProductTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->actingAs($this->adminUser);
    }

    public function testAddingProductToCart(): void
    {
        $response = $this->postJson(route('api.cart.store'), [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $cacheKey = 'user:' . $this->adminUser->id . ':cart';

        $response->assertOk();
        Cache::shouldReceive('put')->with($cacheKey, [$this->product->id => 1]);
    }

    public function testRemovingProductFromCart(): void
    {
        $response = $this->deleteJson(route('api.cart.destroy', ['product_id' => $this->product->id]));

        $cacheKey = 'user:' . $this->adminUser->id . ':cart';

        $response->assertOk();
        Cache::shouldReceive('put')->with($cacheKey, []);
    }

    public function testFetchingCartContents(): void
    {
        $this->postJson(route('api.cart.store'), [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $response = $this->getJson(route('api.cart.index'));

        $cacheKey = 'user:' . $this->adminUser->id . ':cart';

        $response->assertOk();
        Cache::shouldReceive('get')->with($cacheKey);

        $response->assertJson([
            $this->product->id => 1,
        ]);
    }
}
