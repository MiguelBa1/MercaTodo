<?php

namespace Tests\Feature\Web\Cart;

use Inertia\Testing\AssertableInertia;
use Tests\Feature\Utilities\UserTestCase;

class CartControllerTest extends UserTestCase
{
    public function testCartRendersCorrectView(): void
    {
        $response = $this->actingAs($this->customerUser)->get(route('cart.index'));
        $response->assertOk();
        $response->assertStatus(200);
        $response->assertInertia(
            fn (AssertableInertia $page) => $page
            ->component('Cart/Index')
        );
    }
}
