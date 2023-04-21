<?php

namespace Tests\Feature\Api\Admin\ProductManagement;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\Utilities\UserTestCase;
class ProductIndexTest extends UserTestCase
{
    public function testAdminCanGetAllProducts(): void
    {
        Product::factory()->count(5)->create();

        $response = $this->actingAs($this->adminUser)->get(route('admin.api.products.index'));
        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data');
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'sku',
                    'name',
                    'description',
                    'price',
                    'image',
                    'stock',
                    'status',
                    'brand_name',
                    'category_name',
                ]
            ]
        ]);
    }
}
