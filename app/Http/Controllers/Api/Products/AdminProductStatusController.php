<?php

namespace App\Http\Controllers\Api\Products;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class AdminProductStatusController extends Controller
{
    /**
     * @param Product $product
     * @return JsonResponse
     */
    public function update(Product $product): JsonResponse
    {
        $product->setAttribute('status', !$product->getRawOriginal('status'));
        $product->save();
        return response()->json(['message' => 'Product status updated successfully']);
    }
}
