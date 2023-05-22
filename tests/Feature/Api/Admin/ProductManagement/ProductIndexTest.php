<?php

namespace Tests\Feature\Api\Admin\ProductManagement;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\Utilities\UserTestCase;

class ProductIndexTest extends UserTestCase
{
    private const EXPECTED_PRODUCT_COUNT = 5;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        Brand::factory()->create();
        Category::factory()->create();
        Product::factory()->count(self::EXPECTED_PRODUCT_COUNT)->create();
    }

    public function testAdminCanGetAllProducts(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.api.products.index'));

        $response->assertStatus(200);

        $responseData = $response->json();

        $this->assertCount(self::EXPECTED_PRODUCT_COUNT, $responseData['data']);

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
        ], $responseData);

        $productIds = collect($responseData['data'])->pluck('id')->toArray();
        $maxProductId = Product::query()->max('id');
        $minProductId = Product::query()->min('id');
        $expectedOrder = range($maxProductId, $minProductId);
        $this->assertEquals($expectedOrder, $productIds);
    }
}
