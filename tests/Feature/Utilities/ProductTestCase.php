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
        $this->brand = Brand::factory()->create();
        $this->category = Category::factory()->create();

        $this->product = Product::factory()->create([
            'brand_id' => $this->brand->getAttribute('id'),
            'category_id' => $this->category->getAttribute('id')
        ]);
    }

    public function tearDown(): void
    {
        $files = Storage::files('public/images');
        Storage::delete($files);
        parent::tearDown();
    }
}
