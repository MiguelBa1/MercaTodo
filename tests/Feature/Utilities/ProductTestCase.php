<?php

namespace Tests\Feature\Utilities;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

class ProductTestCase extends UserTestCase
{
    use RefreshDatabase;
    protected Brand $brand;

    protected Category $category;

    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->brand = Brand::factory()->create();
        $this->category = Category::factory()->create();
        $this->product = Product::factory()->create([
            'status' => 1,
        ]);
    }
}
