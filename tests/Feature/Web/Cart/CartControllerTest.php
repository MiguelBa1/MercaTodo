<?php

namespace Tests\Feature\Web\Cart;

use Inertia\Testing\AssertableInertia;
use Tests\Feature\Utilities\ProductTestCase;

class CartControllerTest extends ProductTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->actingAs($this->customerUser);
    }

    public function testCartRendersCorrectView(): void
    {
        $this->postJson(route('api.cart.store'), [
            'product_id' => $this->product->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($this->customerUser)->get(route('cart.index'));

        $response->assertOk();

        $response->assertInertia(
            fn (AssertableInertia $page) => $page
                ->component('Cart/Index')
                ->has('cartItems', 1)
                ->has('cartItems.0.product', 6)
                ->has('cartItems.0.quantity')
        );
    }
}
