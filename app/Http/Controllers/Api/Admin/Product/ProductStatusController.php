<?php

namespace App\Http\Controllers\Api\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\Product\ProductService;
use Illuminate\Http\JsonResponse;

class ProductStatusController extends Controller
{
    /**
     * @param Product $product
     * @return JsonResponse
     */
    public function update(Product $product): JsonResponse
    {
        (new ProductService())->toggleStatus($product);

        return response()->json([
            'message' => __('message.updated_status', ['attribute' => 'Product']),
        ]);
    }
}
