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
        $activeProducts = [];

        foreach ($cartData as $productId => $quantity) {
            /** @var Product $product */
            $product = Product::query()
                ->find($productId, ['id', 'name', 'image', 'price', 'status', 'stock']);

            if (!$product || !$product->getRawOriginal('status') || !$product['stock'] > 0) {
                $this->removeProduct($userId, $productId);
            }

            $product['quantity'] = (int)$quantity;
            $activeProducts[] = $product;
        }

        return $activeProducts;
    }

    public function clear(int $userId): void
    {
        Redis::client()->del("user:$userId:cart");
    }

    public function getTotal(array $cart): float
    {
        $total = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::query()->find($productId);

            if ($product) {
                $total += $product->getAttribute('price') * $quantity;
            }
        }

        return $total;
    }
}
