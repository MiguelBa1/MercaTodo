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
        $this->product->status = true;
        $this->product->save();

        $response = $this->actingAs($this->adminUser)->patch(
            route('api.admin.products.status.update', $this->product->id)
        );

        $this->product->refresh();
        $response->assertOk();
        $response->assertJson([
            'message' => __('message.updated_status', ['attribute' => 'Product']),
        ]);

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
        $this->product->status = false;
        $this->product->save();

        $response = $this->actingAs($this->adminUser)->patch(
            route('api.admin.products.status.update', $this->product->id)
        );

        $this->product->refresh();
        $response->assertOk();

        $response->assertJson([
            'message' => __('message.updated_status', ['attribute' => 'Product']),
        ]);

        $this->assertEquals('Active', $this->product->status);

        $this->assertDatabaseHas('products', [
            'id' => $this->product->id,
            'status' => true,
        ]);
    }
}
