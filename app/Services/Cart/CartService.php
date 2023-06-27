<?php

namespace App\Services\Cart;

use App\Services\Product\ProductService;
use Illuminate\Support\Facades\Cache;

class CartService
{
    public function addProduct(int $userId, int $productId, int $quantity): void
    {
        $key = "user:$userId:cart";
        $cart = Cache::get($key, []);
        $cart[$productId] = $quantity;
        Cache::put($key, $cart, 60 * 24 * 7);
    }

    public function removeProduct(int $userId, int $productId): void
    {
        $key = "user:$userId:cart";
        $cart = Cache::get($key, []);

        $cart = array_diff_key($cart, [$productId => '']);

        Cache::put($key, $cart, 60 * 24 * 7);
    }

    public function getCart(int $userId): array
    {
        $key = "user:$userId:cart";
        return Cache::get($key, []);
    }

    public function getProductsWithDetails(int $userId): array
    {
        $cartData = $this->getCart($userId);
        $productIds = array_keys($cartData);

        $productService = new ProductService();
        $productsDetails = $productService->getProductsDetails($productIds);

        foreach ($cartData as $productId => $quantity) {
            if (isset($productsDetails[$productId])) {
                $productsDetails[$productId]['quantity'] = $quantity;
            }
        }

        return $productsDetails;
    }

    public function clear(int $userId): void
    {
        Cache::forget("user:$userId:cart");
    }
}
