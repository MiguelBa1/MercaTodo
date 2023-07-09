<?php

namespace App\Services\Product;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductService
{
    public function getFilteredProducts(array $filters): LengthAwarePaginator
    {
        return Product::query()->with('category:id,name')
            ->select(['id', 'sku', 'name', 'price', 'image', 'category_id'])
            ->when(!empty($filters['category_id']), function ($query) use ($filters) {
                return $query->where('category_id', $filters['category_id']);
            })
            ->when(!empty($filters['brand_id']), function ($query) use ($filters) {
                return $query->where('brand_id', $filters['brand_id']);
            })
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                return $query->where('name', 'like', "%{$filters['search']}%");
            })
            ->where('status', true)
            ->latest('id')
            ->paginate(10);
    }

    public function createProduct(array $data): void
    {
        if (isset($data['image'])) {
            $imageName = (new ProductImageService())->storeImage($data['image']);
            $data['image'] = $imageName;
        }

        $data['status'] = true;

        Product::query()->create($data);
    }

    public function updateProduct(Product $product, array $data): void
    {
        if (isset($data['image']) && $product->image !== $data['image'] && $product->image !== null) {
            (new ProductImageService())->deleteImage($product->image);
        }

        if (isset($data['image'])) {
            $imageName = (new ProductImageService())->storeImage($data['image']);
            $data['image'] = $imageName;
        }

        $product->update($data);
    }

    public function deleteProduct(Product $product): void
    {
        (new ProductImageService())->deleteImage($product->image);
        $product->delete();
    }

    public function toggleStatus(Product $product): void
    {
        $product->status = !$product->getRawOriginal('status');
        $product->save();
    }

    public function getProductsDetails(array $productIds): array
    {
        $products = Product::query()
            ->whereIn('id', $productIds)
            ->get(['id', 'name', 'image', 'price', 'status', 'stock']);

        /** @var array<Product> $productDetails */
        $productDetails = [];

        foreach ($products as $product) {
            $productDetails[$product['id']] = $product;
        }

        return $productDetails;
    }

    public function updateStock(int $product_id, int $quantity, bool $increase = false): void
    {
        /** @var Product $product */
        $product = Product::query()->find($product_id);

        if ($increase) {
            $product->stock += $quantity;
            if ($product->stock > 0) {
                $product->status = true;
            }
        } else {
            $product->stock -= $quantity;
            if ($product->stock <= 0) {
                $product->status = false;
            }
        }
        $product->save();
    }

    public function verifyProductAvailability(Product $product, int $quantity): bool
    {
        return $product->getRawOriginal('status') && $product->stock >= $quantity;
    }
}
