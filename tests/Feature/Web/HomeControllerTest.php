<?php

namespace Tests\Feature\Web;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testRendersHomeViewWithActiveProducts(): void
    {
        Brand::factory(5)->create()->toArray();
        Category::factory(5)->create()->toArray();
        Product::factory(10)->create(['status' => true])->toArray();

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page) => $page
            ->component('Home')
            ->has(
                'products',
                fn (AssertableInertia $page) => $page
                    ->has('data', 10)
                    ->hasAll([
                        'current_page',
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
                    ])
                    ->etc()
            )
            ->has('brands', 5)
            ->has('categories', 5)
            ->has('filters', 0)
        );
    }

    public function testRendersHomeViewWithFilters(): void
    {
        /** @var Brand $brand */
        $brand = Brand::factory()->create();
        /** @var Category $category */
        $category = Category::factory()->create();
        /** @var Product $product */
        $product = Product::factory()->create(
            ['status' => true, 'brand_id' => $brand->id, 'category_id' => $category->id]
        );

        $response = $this->get(route('home', ['brand_id' => $brand->id, 'category_id' => $category->id, 'search' => $product->name]));

        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page) => $page
            ->component('Home')
            ->has(
                'products',
                fn (AssertableInertia $page) => $page
                    ->has('data', 1)
                    ->hasAll([
                        'current_page',
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
                    ])
                    ->etc()
            )
            ->has('brands', 1)
            ->has('categories', 1)
            ->has('filters', 3)
        );
    }
}
