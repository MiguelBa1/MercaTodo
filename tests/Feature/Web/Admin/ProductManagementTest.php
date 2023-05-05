<?php

namespace Tests\Feature\Web\Admin;

use Inertia\Testing\AssertableInertia;
use Tests\Feature\Utilities\ProductTestCase;
use Tests\Feature\Utilities\UserTestCase;

class ProductManagementTest extends ProductTestCase
{

    public function testIndexRendersCorrectView(): void
    {
        $response = $this
            ->actingAs($this->adminUser)
            ->get(route('admin.view.products'));

        $response->assertOk();
        $response->assertStatus(200);
        $response->assertInertia(fn(AssertableInertia $page) => $page
            ->component('Admin/Products/Index')
        );
    }

    public function testEditRendersCorrectView(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.products.edit', $this->product->id));
        $response->assertStatus(200);
        $response->assertInertia(fn(AssertableInertia $page) => $page
            ->component('Admin/Products/Edit')->has('product', fn(AssertableInertia $page) => $page
                ->where('sku', $this->product->sku)
                ->where('name', $this->product->name)
                ->where('description', $this->product->description)
                ->where('price', (string)$this->product->price)
                ->where('image', $this->product->image)
                ->where('stock', $this->product->stock)
                ->where('brand_id', $this->product->brand_id)
                ->where('category_id', $this->product->category_id)
                ->etc()
            )
        );
    }

    public function testCreateRendersCorrectView(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.products.create'));
        $response->assertStatus(200);
        $response->assertInertia(fn(AssertableInertia $page) => $page
            ->component('Admin/Products/Create'));
    }
}
