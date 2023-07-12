<?php

namespace App\Services\Cart;

use App\Exceptions\ProductUnavailableException;
use App\Models\Product;
use App\Services\Product\ProductService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use App\Exceptions\CartValidationException;

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

    /**
     * @param int $userId
     * @return Collection<Product>
     */
    public function getProductsWithDetails(int $userId): Collection
    {
        $cartData = $this->getCart($userId);
        $productIds = array_keys($cartData);

        $products = Product::query()
            ->whereIn('id', $productIds)
            ->get(['id', 'name', 'image', 'price', 'status', 'stock']);

        // add quantity to products
        return $products->map(function ($product) use ($cartData) {
            $product->quantity = $cartData[$product->id];
            return $product;
        });
    }

    public function clear(int $userId): void
    {
        Cache::forget("user:$userId:cart");
    }

    /**
     * @throws CartValidationException
     * @throws ProductUnavailableException
     */
    public function validatedCart(int $userId): Collection
    {
        $cartProducts = $this->getProductsWithDetails($userId);

        if ($cartProducts->isEmpty()) {
            throw CartValidationException::empty();
        }

        $productService = new ProductService();

        foreach ($cartProducts as $cartProduct) {
            if (!$productService->verifyProductAvailability($cartProduct, $cartProduct->quantity)) {
                throw ProductUnavailableException::unavailable($cartProduct->name);
            }
        }

        return $cartProducts;
    }
}
