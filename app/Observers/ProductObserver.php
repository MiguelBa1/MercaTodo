<?php

namespace App\Observers;

use App\Models\Product;

class ProductObserver
{
    /**
     * Handle the Product "updating" event.
     *
     * @param Product $product
     * @return void
     */
    public function updating(Product $product): void
    {
        if ($product->isDirty('stock')) {
            $product->checkStock();
        }
    }
}
