<?php

namespace Tests\Unit\Http\Requests;

use App\Http\Requests\CartStoreRequest;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Utilities\ProductTestCase;

class CartStoreRequestTest extends ProductTestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Data provider for validation test.
     *
     * @return array
     */
    public static function validationDataProvider(): array
    {
        return [
            'valid_data' => [
                'data' => [
                    'product_id' => 'valid',
                    'quantity' => 5,
                ],
                'should_pass' => true,
                'error_fields' => [],
            ],
            'invalid_product_id' => [
                'data' => [
                    'product_id' => 999,
                    'quantity' => 5,
                ],
                'should_pass' => false,
                'error_fields' => ['product_id'],
            ],
            'invalid_quantity' => [
                'data' => [
                    'product_id' => 'valid',
                    'quantity' => 20,
                ],
                'should_pass' => false,
                'error_fields' => ['quantity'],
            ],
        ];
    }

    /**
     * @dataProvider validationDataProvider
     * @param array $data
     * @param bool $shouldPass
     * @param array $errorFields
     */
    public function testValidation(array $data, bool $shouldPass, array $errorFields): void
    {
        $product = Product::factory()->create(['stock' => 10]);

        if ($data['product_id'] === 'valid') {
            $data['product_id'] = $product->getAttribute('id');
        }

        $request = new CartStoreRequest();

        $request->replace($data);
        $validator = $this->app['validator']->make($request->all(), $request->rules());

        if (!$validator->fails()) {
            $this->assertEquals($shouldPass, !$validator->fails());
        }

        foreach ($errorFields as $field) {
            $this->assertTrue($validator->errors()->has($field));
        }
    }
}
