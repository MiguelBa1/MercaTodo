<?php

namespace Tests\Feature\Api\Admin\Products;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\Utilities\ProductTestCase;

class ProductStoreTest extends ProductTestCase
{
    /**
     * @dataProvider validProductData
     * @param array $productData
     * @return void
     */
    public function testAdminCanCreateProduct(array $productData): void
    {
        $productData['brand_id'] = $this->brand->id;
        $productData['category_id'] = $this->category->id;
        $response = $this->actingAs($this->adminUser)->post(route('api.admin.products.store'), $productData);

        $response->assertCreated();
        $response->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'sku',
                'name',
                'description',
                'price',
                'image',
                'stock',
                'status',
                'brand_id',
                'category_id',
            ],
        ]);

        $imageName = time() . '_' . $productData['image']->getClientOriginalName();
        $this->assertDatabaseHas('products', [
            'sku' => $productData['sku'],
            'name' => $productData['name'],
            'description' => $productData['description'],
            'price' => $productData['price'],
            'image' => $imageName,
            'stock' => $productData['stock'],
            'status' => 1,
            'brand_id' => $productData['brand_id'],
            'category_id' => $productData['category_id'],
        ]);

        Storage::disk('public')->assertExists('images/' . $imageName);
    }

    /**
     * @dataProvider validProductData
     * @param array $productData
     * @return void
     */
    public function testCustomerCanNotCreateProduct(array $productData): void
    {
        $response = $this->actingAs($this->customerUser)->post(route('api.admin.products.store'), $productData);

        $response->assertForbidden();
    }

    public static function validProductData(): array
    {
        $image = UploadedFile::fake()->image('test-image.png');
        return [
            'valid product data' => [
                [
                    'sku' => fake()->unique()->ean8(),
                    'name' => 'Test Product',
                    'description' => 'Test Description',
                    'price' => 1000.00,
                    'image' => $image,
                    'stock' => 10,
                ]
            ]
        ];
    }
}
