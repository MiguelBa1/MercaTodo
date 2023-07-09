<?php

namespace Tests\Feature\Requests\Admin;

use Illuminate\Http\UploadedFile;
use Tests\Feature\Utilities\ProductTestCase;

class ProductRequestTest extends ProductTestCase
{
    /**
     * @dataProvider invalidProductData
     * @param string $field
     * @param string|int $value
     * @param string $message
     * @return void
     */
    public function testInvalidProductDataFails(string $field, string|int $value, string $message): void
    {
        $image = UploadedFile::fake()->image('test-image.png');

        $response = $this->actingAs($this->adminUser)->post(route('api.admin.products.store'), [
            'sku' => $field === 'sku' ? $value : fake()->ean8(),
            'name' => $field === 'name' ? $value : 'Test Product',
            'description' => $field === 'description' ? $value : 'Test Description',
            'price' => $field === 'price' ? $value : 1000,
            'image' => $field === 'image' ? $value : $image,
            'stock' => $field === 'stock' ? $value : 10,
            'brand_id' => $field === 'brand_id' ? $value : $this->brand->getAttribute('id'),
            'category_id' => $field === 'category_id' ? $value : $this->category->getAttribute('id')
        ]);

        $response->assertSessionHasErrors($field, $message);
        $response->assertRedirect();
    }


    public static function invalidProductData(): array
    {
        return [
            'sku is required' => [
                'sku',
                '',
                'The sku field is required.'
            ],
            'name is required' => [
                'name',
                '',
                'The name field is required.'
            ],
            'name must be a string' => [
                'name',
                123,
                'The name must be a string.'
            ],
            'name must be at least 3 characters' => [
                'name',
                'ab',
                'The name must be at least 3 characters.'
            ],
            'name must be at most 100 characters' => [
                'name',
                str_repeat('a', 101),
                'The name must be at most 255 characters.'
            ],
            'description is required' => [
                'description',
                '',
                'The description field is required.'
            ],
            'description must be a string' => [
                'description',
                123,
                'The description must be a string.'
            ],
            'description must be at least 3 characters' => [
                'description',
                'ab',
                'The description must be at least 3 characters.'
            ],
            'description must be at most 500 characters' => [
                'description',
                str_repeat('a', 501),
                'The description must be at most 500 characters.'
            ],
            'price is required' => [
                'price',
                '',
                'The price field is required.'
            ],
            'price must be numeric' => [
                'price',
                'invalid-price',
                'The price must be a number.'
            ],
            'price must be at least 0' => [
                'price',
                -1,
                'The price must be at least 0.'
            ],
            'price must be at most 100000' => [
                'price',
                100001,
                'The price must be at most 100000.'
            ],
            'image must be an image' => [
                'image',
                'invalid-image',
                'The image must be an image.'
            ],
            'stock is required' => [
                'stock',
                '',
                'The stock field is required.'
            ],
            'stock must be numeric' => [
                'stock',
                'invalid-stock',
                'The stock must be a number.'
            ],
            'stock must be at least 0' => [
                'stock',
                -1,
                'The stock must be at least 0.'
            ],
            'stock must be at most 100000' => [
                'stock',
                100001,
                'The stock must be at most 100000.'
            ],
            'brand_id is required' => [
                'brand_id',
                '',
                'The brand id field is required.'
            ],
            'brand_id must exist' => [
                'brand_id',
                '999',
                'The selected brand id is invalid.'
            ],
            'category_id is required' => [
                'category_id',
                '',
                'The category id field is required.'
            ],
            'category_id must exist' => [
                'category_id',
                '999',
                'The selected category id is invalid.'
            ],
        ];
    }
}
