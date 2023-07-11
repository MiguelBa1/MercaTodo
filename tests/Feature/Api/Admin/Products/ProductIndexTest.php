<?php

namespace Tests\Feature\Api\Admin\Products;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilities\UserTestCase;
use App\Models\Product;

class ProductIndexTest extends UserTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->actingAs($this->adminUser);
    }

    public function testAdminCanListProducts(): void
    {
        Brand::factory()->count(5)->create();
        Category::factory()->count(5)->create();
        Product::factory()->count(5)->create();

        $response = $this->get(route('api.admin.products.index'));

        $response->assertOk();

        $response->assertJsonStructure([
            'message',
            'data' => [
                'current_page',
                'data' => [
                    '*' => [
                        'id',
                        'sku',
                        'name',
                        'price',
                        'stock',
                        'status',
                        'brand_id',
                        'category_id',
                        'category' => [
                            'id',
                            'name',
                        ],
                        'brand' => [
                            'id',
                            'name',
                        ],
                    ],
                ],
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'links' => [
                    '*' => [
                        'url',
                        'label',
                        'active',
                    ],
                ],
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total',
            ],
        ]);
    }
}
