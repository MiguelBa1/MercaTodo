<?php

namespace App\Http\Controllers\Api\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Products\ProductRequest;
use App\Models\Product;
use App\Services\Product\ProductService;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $products = (new ProductService())->getAllProducts();

        return response()->json([
            'message' => __('message.retrieved', ['attribute' => 'Products']),
            'data' => $products,
        ]);
    }

    /**
     * @param ProductRequest $request
     * @return JsonResponse
     */
    public function store(ProductRequest $request): JsonResponse
    {
        $data = $request->validated();
        $product = (new ProductService())->createProduct($data);

        return response()->json([
            'message' => __('message.created', ['attribute' => 'Product']),
            'data' => $product,
        ], 201);
    }

    /**
     * @param Product $product
     * @return JsonResponse
     */
    public function show(Product $product): JsonResponse
    {
        return response()->json([
            'message' => __('message.retrieved', ['attribute' => 'Product']),
            'data' => $product->load('category:id,name', 'brand:id,name')
                ->only('id', 'name', 'price', 'stock', 'description', 'image', 'category', 'brand'),
        ]);
    }

    /**
     * @param ProductRequest $request
     * @param Product $product
     * @return JsonResponse
     */
    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        $data = $request->validated();

        return response()->json([
            'message' => __('message.updated', ['attribute' => 'Product']),
            'data' => (new ProductService())->updateProduct($product, $data),
        ]);
    }

    /**
     * @param Product $product
     * @return JsonResponse
     */
    public function destroy(Product $product): JsonResponse
    {
        (new ProductService())->deleteProduct($product);

        return response()->json([
            'message' => __('message.deleted', ['attribute' => 'Product']),
        ]);
    }
}
