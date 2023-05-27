<?php

namespace Tests\Feature\Api\Cart;

use App\Models\Product;
use Illuminate\Support\Facades\Redis;
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
            'product_id' => $this->product->getAttribute('id'),
            'quantity' => 1,
        ]);

        $redisKey = 'user:' . $this->adminUser->getAttribute('id') . ':cart';

        $response->assertOk();
        Redis::shouldReceive('hset')->with($redisKey, $this->product->getAttribute('id'), 1);
    }

    public function testRemovingProductFromCart(): void
    {
        $response = $this->deleteJson(route('api.cart.destroy', ['product_id' => $this->product->getAttribute('id')]));

        $redisKey = 'user:' . $this->adminUser->getAttribute('id') . ':cart';

        $response->assertOk();
        Redis::shouldReceive('hdel')->with($redisKey, $this->product->getAttribute('id'));
    }

    public function testFetchingCartContents(): void
    {
        $this->postJson(route('api.cart.store'), [
            'product_id' => $this->product->getAttribute('id'),
            'quantity' => 1,
        ]);

        $response = $this->getJson(route('api.cart.index'));

        $redisKey = 'user:' . $this->adminUser->getAttribute('id') . ':cart';

        $response->assertOk();
        Redis::shouldReceive('hgetall')->with($redisKey);
        $response->assertJson([$this->product->getAttribute('id') => 1]);
    }

    public function testFetchingOnlyActiveProductsInStock(): void
    {
        $product1 = Product::factory()->create(['stock' => 1, 'status' => true]);
        $product2 = Product::factory()->create(['stock' => 0, 'status' => true]);
        $product3 = Product::factory()->create(['stock' => 1, 'status' => false]);

        $this->postJson(route('api.cart.store'), ['product_id' => $product1->getAttribute('id'), 'quantity' => 1]);
        $this->postJson(route('api.cart.store'), ['product_id' => $product2->getAttribute('id'), 'quantity' => 1]);
        $this->postJson(route('api.cart.store'), ['product_id' => $product3->getAttribute('id'), 'quantity' => 1]);

        $response = $this->getJson(route('api.cart.index'));

        $response->assertOk();
        $response->assertJson([$product1->getAttribute('id') => 1]);
    }
}
