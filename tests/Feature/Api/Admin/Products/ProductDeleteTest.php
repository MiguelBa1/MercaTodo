<?php

namespace Tests\Feature\Api\Admin\Products;

use Illuminate\Support\Facades\Storage;
use Tests\Feature\Utilities\ProductTestCase;

class ProductDeleteTest extends ProductTestCase
{
    public function testAdminCanDeleteProduct(): void
    {
        $response = $this->actingAs($this->adminUser)->delete(route('api.admin.products.destroy', $this->product->id));

        $response->assertOk();

        $response->assertJson([
            'message' => __('message.deleted', ['attribute' => 'Product']),
        ]);

        Storage::disk('public')->assertMissing($this->product->image);

        $this->assertDatabaseMissing('products', [
            'id' => $this->product->id,
        ]);
    }
}
