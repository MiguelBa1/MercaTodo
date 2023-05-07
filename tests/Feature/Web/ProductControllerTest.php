<?php

namespace Tests\Feature\Web;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    // Create brands, categories, and products
    protected array $brand = [];
    protected array $category = [];
    protected array $product = [];

    public function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->brand = Brand::factory(5)->create()->toArray();
        $this->category = Category::factory(5)->create()->toArray();
        $this->product = Product::factory(10)->create(['status' => true])->toArray();
    }

    public function testViewRenders(): void
    {
        $response = $this->get(route('products.show', $this->product[0]['id']));
        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Products/Show')
            ->has('product')
            ->has('relatedProducts')
        );
    }

    public function testRelatedProductsHasTheSameCategory(): void
    {
        $response = $this->get(route('products.show', $this->product[0]['id']));
        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Products/Show', function ($page) {
                $relatedProducts = $page['relatedProducts'];
                $relatedProducts->each(function ($product) {
                    $this->assertEquals($product['category_id'], $this->product[0]['category_id']);
                });
            })
        );
    }

}
