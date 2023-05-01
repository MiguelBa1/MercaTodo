<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Products\ProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    /**
     * @param ProductRequest $request
     * @return JsonResponse
     */
    public function store(ProductRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (isset($data['image'])) {
            $imageName = $this->storeImage($data['image']);
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
            $this->deleteImage($product->getAttribute('image'));
        }

        if (isset($data['image'])) {
            $imageName = $this->storeImage($data['image']);
            $data['image'] = $imageName;
        }

        $product->update($data);
        return response()->json(['message' => 'Product updated successfully']);
    }

    /**
     * @return LengthAwarePaginator
     */
    public function index(): LengthAwarePaginator
    {
        return Product::query()->join('categories', 'categories.id', '=', 'products.category_id')
            ->join('brands', 'brands.id', '=', 'products.brand_id')
            ->select(
                'products.id',
                'products.sku',
                'products.name',
                'products.description',
                'products.price',
                'products.image',
                'products.stock',
                'products.status',
                'categories.name as category_name',
                'brands.name as brand_name'
            )
            ->orderBy('products.id', 'desc')->paginate(10);
    }

    /**
     * @param Product $product
     * @return JsonResponse
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();
        $this->deleteImage($product->getAttribute('image'));
        return response()->json(['message' => 'Product deleted successfully']);
    }

    /**
     * @param Product $product
     * @return JsonResponse
     */
    public function updateStatus(Product $product): JsonResponse
    {
        $product->setAttribute('status', !$product->getRawOriginal('status'));
        $product->save();
        return response()->json(['message' => 'Product status updated successfully']);
    }

    /**
     * @param UploadedFile $image
     * @return string
     */
    protected function storeImage(UploadedFile $image): string
    {
        $imageIntervention = Image::make($image);
        $imageName = time() . '_' . $image->getClientOriginalName();
        Storage::disk('public')->put('images/' . $imageName, $imageIntervention->stream());

        return $imageName;
    }

    /**
     * @param string $imageName
     * @return void
     */
    protected function deleteImage(string $imageName): void
    {
        Storage::disk('public')->delete('images/' . $imageName);
    }
}
