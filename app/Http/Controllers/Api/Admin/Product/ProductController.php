<?php

namespace App\Http\Controllers\Api\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Products\ProductRequest;
use App\Models\Product;
use App\Services\Product\ProductService;

class ProductController extends Controller
{
    /**
     * @param ProductRequest $request
     * @return void
     */
    public function store(ProductRequest $request): void
    {
        $data = $request->validated();
        (new ProductService())->createProduct($data);
    }

    /**
     * @param ProductRequest $request
     * @param Product $product
     * @return void
     */
    public function update(ProductRequest $request, Product $product): void
    {
        $data = $request->validated();
        (new ProductService())->updateProduct($product, $data);
    }

    /**
     * @param Product $product
     * @return void
     */
    public function destroy(Product $product): void
    {
        (new ProductService())->deleteProduct($product);
    }
}
