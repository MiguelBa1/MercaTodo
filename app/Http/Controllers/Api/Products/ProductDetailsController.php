<?php

namespace App\Http\Controllers\Api\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductsDetailsRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductDetailsController extends Controller
{
    public function show(ProductsDetailsRequest $request): JsonResponse
    {
        $products = [];

        foreach ($request->validated()['products'] as $productId) {
            $product = Product::query()->find($productId['id']);
            if ($product) {
                $products[] = $product->only(['id', 'name', 'image', 'price', 'stock']);
                // Add the quantity from the request to the response
                $products[count($products) - 1]['quantity'] = $productId['quantity'];
            }
        }

        return response()->json($products);
    }
}
