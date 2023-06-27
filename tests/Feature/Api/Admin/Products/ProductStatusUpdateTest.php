<?php

namespace Tests\Feature\Api\Admin\Products;

use Tests\Feature\Utilities\ProductTestCase;

class ProductStatusUpdateTest extends ProductTestCase
{
    /**
     * @return void
     */
    public function testAdminCanUpdateActiveProductStatus(): void
    {
        $this->product->setAttribute('status', true);
        $this->product->save();

        $response = $this->actingAs($this->adminUser)->patch(
            route('admin.api.products.status.update', $this->product->id)
        );

        $this->product->refresh();
        $response->assertOk();
        $this->assertEquals('Inactive', $this->product->status);

        $this->assertDatabaseHas('products', [
            'id' => $this->product->id,
            'status' => false,
        ]);
    }

    /**
     * @return void
     */
    public function testAdminCanUpdateInactiveProductStatus(): void
    {
        $this->product->setAttribute('status', false);
        $this->product->save();

        $response = $this->actingAs($this->adminUser)->patch(
            route('admin.api.products.status.update', $this->product->id)
        );

        $this->product->refresh();
        $response->assertOk();
        $this->assertEquals('Active', $this->product->status);

        $this->assertDatabaseHas('products', [
            'id' => $this->product->id,
            'status' => true,
        ]);
    }
}
