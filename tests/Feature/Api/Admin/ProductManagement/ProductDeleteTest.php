<?php

namespace Tests\Feature\Api\Admin\ProductManagement;

use Illuminate\Support\Facades\Storage;
use Tests\Feature\Utilities\ProductTestCase;

class ProductDeleteTest extends ProductTestCase
{
    public function testAdminCanDeleteProduct(): void
    {
        $response = $this->actingAs($this->adminUser)->delete(route('admin.api.products.destroy', $this->product->getAttribute('id')));

        $response->assertOk();

        Storage::disk('public')->assertMissing($this->product->getAttribute('image'));

        $this->assertDatabaseMissing('products', [
            'id' => $this->product->getAttribute('id'),
        ]);
    }
}
