<?php

namespace App\Services;

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
        return Redis::client()->hgetall("user:$userId:cart");
    }

    public function clearCart(int $userId): void
    {
        Redis::client()->del("user:$userId:cart");
    }
}
