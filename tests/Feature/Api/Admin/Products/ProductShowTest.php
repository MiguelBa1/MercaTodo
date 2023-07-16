<?php

namespace Tests\Feature\Api\Admin\Products;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilities\UserTestCase;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;

class ProductShowTest extends UserTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->actingAs($this->adminUser);
    }

    public function testAdminCanShowProduct(): void
    {
        Brand::factory()->count(5)->create();
        Category::factory()->count(5)->create();
        $product = Product::factory()->create();


        $response = $this->get(route('api.admin.product.show', $product->id));

        $response->assertOk();

        $response->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'name',
                'description',
                'price',
                'image',
                'stock',
                'category' => [
                    'id',
                    'name',
                ],
                'brand' => [
                    'id',
                    'name',
                ],
            ],
        ]);
    }
}
