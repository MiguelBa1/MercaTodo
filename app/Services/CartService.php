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

    public function getProducts(int $userId): array
    {
        $cartData = Redis::client()->hgetall("user:$userId:cart");
        $activeCartData = [];

        foreach ($cartData as $productId => $quantity) {
            $product = Product::query()->find($productId);

            if ($product && $product->getAttribute('stock') > 0 && $product->getRawOriginal('status')) {
                $activeCartData[$productId] = $quantity;
            } else {
                Redis::client()->hdel("user:$userId:cart", $productId);
            }
        }

        return $activeCartData;
    }
}
