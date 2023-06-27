<?php

namespace App\Http\Controllers\Api\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\Product\ProductService;

class ProductStatusController extends Controller
{
    /**
     * @param Product $product
     * @return void
     */
    public function update(Product $product): void
    {
        (new ProductService())->toggleStatus($product);
    }
}
