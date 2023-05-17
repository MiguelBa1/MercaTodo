<?php

namespace Tests\Feature\Api\Admin\ProductManagement;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\Utilities\UserTestCase;

class ProductIndexTest extends UserTestCase
{
    public function testAdminCanGetAllProducts(): void
    {
        Storage::fake('public');
        Brand::factory()->create();
        Category::factory()->create();
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
