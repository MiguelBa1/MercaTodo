<?php

namespace Tests\Feature\Api\Admin\ProductManagement;

use Tests\Feature\Utilities\ProductTestCase;

class ProductDeleteTest extends ProductTestCase
{
    public function testAdminCanDeleteProduct(): void
    {
        $response = $this->actingAs($this->adminUser)->delete(route('admin.api.products.destroy', $this->product->getAttribute('id')));

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Product deleted successfully']);
    }
}
