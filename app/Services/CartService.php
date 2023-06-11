<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Redis;

class CartService
{
    public function addProduct(int $userId, int $productId, int $quantity): void
    {
        Redis::client()->hset("user:$userId:cart", $productId, $quantity);
    }

    public function removeProduct(int $userId, int $productId): void
    {
        Redis::client()->hdel("user:$userId:cart", $productId);
    }

    public function getProductsWithDetails(int $userId): array
    {
        $cartData = Redis::client()->hgetall("user:$userId:cart");
        /** @var array<Product> $activeProducts */
        $activeProducts = [];

        foreach ($cartData as $productId => $quantity) {
            /** @var Product $product */
            $product = Product::query()
                ->find($productId, ['id', 'name', 'image', 'price', 'status', 'stock']);

            $product['quantity'] = (int)$quantity;
            $activeProducts[] = $product;
        }

        return $activeProducts;
    }

    public function clear(int $userId): void
    {
        Redis::client()->del("user:$userId:cart");
    }
}
