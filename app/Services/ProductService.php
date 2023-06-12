<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
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
        return $product->getRawOriginal('status') && $product->stock >= $quantity && $product->stock > 0;
    }
}
