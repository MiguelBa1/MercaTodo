<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    public function updateStock(int $product_id, int $quantity, bool $increase = false): void
    {
        /* @var Product $product */
        $product = Product::query()->find($product_id)->first();

        if ($increase) {
            $product->stock += $quantity;
            if ($product->stock > 0) {
                $product->status = 1;
            }
        } else {
            $product->stock -= $quantity;
            if ($product->stock <= 0) {
                $product->status = 0;
            }
        }

        $product->save();
    }
}
