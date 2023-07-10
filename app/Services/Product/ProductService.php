<?php

namespace App\Services\Product;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ProductService
{
    public function getAllProducts(): LengthAwarePaginator
    {
        return Product::query()
            ->with(['category:id,name', 'brand:id,name'])
            ->select(
                'id',
                'sku',
                'name',
                'price',
                'stock',
                'status',
                'category_id',
                'brand_id',
            )
            ->latest('id')
            ->paginate(10);
    }

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

    public function createProduct(array $data): Builder|Model
    {
        if (isset($data['image'])) {
            $imageName = (new ProductImageService())->storeImage($data['image']);
            $data['image'] = $imageName;
        }

        $data['status'] = true;

        return Product::query()->create($data);
    }

    public function updateProduct(Product $product, array $data): Product
    {
        if (isset($data['image']) && $product->image !== $data['image'] && $product->image !== null) {
            (new ProductImageService())->deleteImage($product->image);
        }

        if (isset($data['image'])) {
            $imageName = (new ProductImageService())->storeImage($data['image']);
            $data['image'] = $imageName;
        }

        $product->update($data);

        return $product;
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

    public function getRelatedProducts(Product $product): Collection
    {
        return Product::query()
            ->with('category:id,name')
            ->where('category_id', $product->category_id)
            ->where('status', true)
            ->whereKeyNot($product->getKey())
            ->select(['id', 'name', 'price', 'image', 'category_id'])
            ->inRandomOrder()
            ->limit(6)
            ->get();
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
