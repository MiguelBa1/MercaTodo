<?php

namespace Tests\Feature\Api;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HomeControllerTest extends TestCase
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

    public function testIndexReturnOnlyActiveProducts(): void
    {
        $response = $this->get(route('api.home.index'));
        $response->assertStatus(200);
        $response->assertJsonCount(10, 'data');
        $response->assertJsonStructure([
            'current_page',
            'data' => [
                '*' => [
                    'id',
                    'sku',
                    'name',
                    'price',
                    'image',
                    'category_name',
                ],
            ],
            'first_page_url',
            'from',
            'last_page',
            'last_page_url',
            'links',
            'next_page_url',
            'path',
            'per_page',
            'prev_page_url',
            'to',
            'total',
        ]);
    }

    public function testIndexWithCategoryId(): void
    {
        $response = $this->get(route('api.home.index', ['category_id' => $this->category[0]['id']]));
        $response->assertStatus(200);

        // Assert that the response products has the same category name
        foreach ($response->json('data') as $product) {
            $this->assertEquals($this->category[0]['name'], $product['category_name']);
        }
    }

    public function testIndexWithBrandId(): void
    {
        $response = $this->get(route('api.home.index', ['brand_id' => $this->brand[0]['id']]));
        $response->assertStatus(200);

        // Assert products has the same brand name
        foreach ($response->json('data') as $product) {
            $this->assertEquals(
                $this->brand[0]['name'],
                Product::query()->where('id', $product['id'])->first()->brand->name
            );
        }
    }

    public function testIndexWithSearch(): void
    {
        $response = $this->get(route('api.home.index', ['search' => $this->product[0]['name']]));
        $response->assertStatus(200);

        // Assert products has the name like the search string
        foreach ($response->json('data') as $product) {
            $this->assertStringContainsString($this->product[0]['name'], $product['name']);
        }
    }
}
