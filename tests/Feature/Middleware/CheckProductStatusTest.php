<?php

namespace Tests\Feature\Middleware;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilities\ProductTestCase;

class CheckProductStatusTest extends ProductTestCase
{
    use RefreshDatabase;

    public function testRedirectsIfProductIsNotActive()
    {
        $this->actingAs($this->customerUser);

        $this->product->update(['status' => false]);

        $this->get(route('product.show', $this->product))
            ->assertRedirect(route('home'));
    }

    public function testContinueIfProductIsActive()
    {
        $this->actingAs($this->customerUser);

        $this->get(route('product.show', $this->product))
            ->assertOk();
    }
}
