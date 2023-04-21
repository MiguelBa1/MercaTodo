<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Products\ProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
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

        try {
            $product = Product::create($data);
            return response()->json(['message' => 'Product created successfully', 'product' => $product]);
        } catch (\Exception $e) {
            if (isset($imageName)) {
                $this->deleteImage($imageName);
            }
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * @param ProductRequest $request
     * @param Product $product
     * @return JsonResponse
     */
    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        $data = $request->validated();

        if (isset($data['image'])) {
            $imageName = $this->storeImage($data['image']);
            $data['image'] = $imageName;
        }

        try {
            $product->update($data);
            return response()->json(['message' => 'Product updated successfully']);
        } catch (\Exception $e) {
            if (isset($imageName)) {
                $this->deleteImage($imageName);
            }
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * @return LengthAwarePaginator
     */
    public function index(): LengthAwarePaginator
    {
        return Product::index()->paginate(10);
    }

    /**
     * @param Product $product
     * @return JsonResponse
     */
    public function destroy(Product $product): JsonResponse
    {
        try {
            $product->delete();
            $this->deleteImage($product->getAttribute('image'));
            return response()->json(['message' => 'Product deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * @param Product $product
     * @return JsonResponse
     */
    public function updateStatus(Product $product): JsonResponse
    {
        try {
            $product->setAttribute('status', !$product->getRawOriginal('status'));
            $product->save();
            return response()->json(['message' => 'Product status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * @param UploadedFile $image
     * @return string
     */
    protected function storeImage(UploadedFile $image) : string
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
