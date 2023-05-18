<?php

namespace Tests\Feature\Api\Admin\ProductManagement;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\Utilities\ProductTestCase;

class ProductUpdateTest extends ProductTestCase
{
    /**
     * @dataProvider validProductData
     * @param array $productData
     * @return void
     */
    public function testAdminCanUpdateProduct(array $productData): void
    {
        $response = $this->actingAs($this->adminUser)->post(
            route('admin.api.products.update', $this->product->getAttribute('id')),
            $productData
        );

        $response->assertOk();
        $response->assertJson(['message' => 'Product updated successfully']);

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
    public function testCustomerCanNotUpdateProduct(array $productData): void
    {
        $response = $this->actingAs($this->customerUser)->post(
            route('admin.api.products.update', $this->product->getAttribute('id')),
            $productData
        );

        $response->assertForbidden();
    }

    /**
     * @dataProvider validProductData
     * @param array $productData
     * @return void
     */
    public function testProductCanUpdateWithEmptyImage(array $productData): void
    {
        $productData['image'] = null;
        $response = $this->actingAs($this->adminUser)->post(
            route('admin.api.products.update', $this->product->getAttribute('id')),
            $productData
        );

        $response->assertOk();
        $response->assertJson(['message' => 'Product updated successfully']);

        $this->assertDatabaseHas('products', [
            'sku' => $productData['sku'],
            'name' => $productData['name'],
            'description' => $productData['description'],
            'price' => $productData['price'],
            'image' => null,
            'stock' => $productData['stock'],
            'status' => 1,
            'brand_id' => $productData['brand_id'],
            'category_id' => $productData['category_id'],
        ]);
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
