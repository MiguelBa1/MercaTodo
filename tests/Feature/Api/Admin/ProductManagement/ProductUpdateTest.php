<?php

namespace Tests\Feature\Api\Admin\ProductManagement;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\Utilities\ProductTestCase;

class ProductUpdateTest extends ProductTestCase
{
    public function testAdminCanUpdateProductWithAnImage(): void
    {
        $file = new UploadedFile(
            __DIR__ . '/../../../Utilities/test-image.png',
            'test-image.png',
            'image/png',
            null,
            true
        );

        $response = $this->actingAs($this->adminUser)->post(route('admin.api.products.update', $this->product->getAttribute('id')), [
            'sku' => 'TEST-PRODUCT',
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 1000,
            'image' => $file,
            'stock' => 10,
            'status' => true,
            'brand_id' => $this->brand->getAttribute('id'),
            'category_id' => $this->category->getAttribute('id')
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Product updated successfully']);
        $this->assertFileExists(Storage::disk('public')->path('images/' . time() . '_test-image.png'));
    }

    public function testAdminCanUpdateProductWithoutAnImage(): void
    {
        $response = $this->actingAs($this->adminUser)->post(route('admin.api.products.update', $this->product->getAttribute('id')), [
            'sku' => 'TEST-PRODUCT',
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 1000,
            'stock' => 10,
            'status' => true,
            'brand_id' => $this->brand->getAttribute('id'),
            'category_id' => $this->category->getAttribute('id')
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Product updated successfully']);
    }

    public function testAdminCanNotUpdateProductWithInvalidData(): void
    {
        $response = $this->actingAs($this->adminUser)->post(route('admin.api.products.update', $this->product->getAttribute('id')), [
            'sku' => 'TEST-PRODUCT',
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 1000,
            'image' => 'invalid-image',
            'stock' => 10,
            'status' => true,
            'brand_id' => $this->brand->getAttribute('id'),
            'category_id' => $this->category->getAttribute('id')
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['image']);
    }
}
