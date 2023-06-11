<?php

namespace Tests\Feature\Api\Cart;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilities\ProductTestCase;

class CartProductControllerTest extends ProductTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->actingAs($this->customerUser);
    }

    public function testCartProductIndex(): void
    {
        $this->postJson(route('api.cart.store'), [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $response = $this->getJson(route('api.cart.products.index'));

        $response->assertOk();
        $response->assertJson([
            $this->product->id => [
                    'id' => $this->product->id,
                    'name' => $this->product->name,
                    'price' => $this->product->price,
                    'quantity' => 1,
                    'image' => $this->product->image,
                    'status' => $this->product->status,
            ],
        ]);
    }
}
