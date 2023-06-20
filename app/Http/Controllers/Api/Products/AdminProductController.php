<?php

namespace App\Http\Controllers\Api\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Products\ProductRequest;
use App\Models\Product;
use App\Services\ProductImageService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

class AdminProductController extends Controller
{
    protected ProductImageService $imageService;

    public function __construct(ProductImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * @param ProductRequest $request
     * @return JsonResponse
     */
    public function store(ProductRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (isset($data['image'])) {
            $imageName = $this->imageService->storeImage($data['image']);
            $data['image'] = $imageName;
        }

        $data['status'] = true;

        $product = Product::query()->create($data);
        return response()->json(['message' => 'Product created successfully', 'product' => $product]);
    }

    /**
     * @param ProductRequest $request
     * @param Product $product
     * @return JsonResponse
     */
    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        $data = $request->validated();

        if (isset($data['image']) && $product->getAttribute('image') !== $data['image']) {
            $this->imageService->deleteImage($product->getAttribute('image'));
        }

        if (isset($data['image'])) {
            $imageName = $this->imageService->storeImage($data['image']);
            $data['image'] = $imageName;
        }

        $product->update($data);
        return response()->json(['message' => 'Product updated successfully']);
    }

    /**
     * @param Product $product
     * @return JsonResponse
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();
        $this->imageService->deleteImage($product->getAttribute('image'));
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
