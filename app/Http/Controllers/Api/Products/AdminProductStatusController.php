<?php

namespace App\Http\Controllers\Api\Products;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ProductService;

class AdminProductStatusController extends Controller
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
