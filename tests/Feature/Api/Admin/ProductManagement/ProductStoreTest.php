<?php

namespace Tests\Feature\Api\Admin\ProductManagement;

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
        $response = $this->actingAs($this->adminUser)->post(route('admin.api.products.store'), $productData);

        $response->assertOk();
        $response->assertJson(['message' => 'Product created successfully']);

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
        $response = $this->actingAs($this->customerUser)->post(route('admin.api.products.store'), $productData);

        $response->assertStatus(403);
    }

    public static function validProductData(): array
    {
        $image = UploadedFile::fake()->image('test-image.png');
        return [
            'valid product data' => [
                [
                    'sku' => 'TEST-PRODUCT',
                    'name' => 'Test Product',
                    'description' => 'Test Description',
                    'price' => 1000,
                    'image' => $image,
                    'stock' => 10,
                    'brand_id' => 1,
                    'category_id' => 1,
                ]
            ]
        ];
    }
}
