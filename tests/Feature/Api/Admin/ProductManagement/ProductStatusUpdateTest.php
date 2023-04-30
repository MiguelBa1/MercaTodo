<?php

namespace Tests\Feature\Api\Admin\ProductManagement;

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
            route('admin.api.products.updateStatus', $this->product->getAttribute('id'))
        );

        $this->product->refresh();
        $response->assertStatus(200);
        $this->assertEquals('Inactive', $this->product->status);
    }

    /**
     * @return void
     */
    public function testAdminCanUpdateInactiveProductStatus(): void
    {
        $this->product->setAttribute('status', false);
        $this->product->save();


        $response = $this->actingAs($this->adminUser)->patch(
            route('admin.api.products.updateStatus', $this->product->getAttribute('id'))
        );

        $this->product->refresh();
        $response->assertStatus(200);
        $this->assertEquals('Active', $this->product->status);
    }
}
