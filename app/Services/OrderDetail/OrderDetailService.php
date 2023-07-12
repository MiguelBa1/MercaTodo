<?php

namespace App\Services\OrderDetail;

use App\Models\Order;
use App\Models\Product;
use App\Services\Product\ProductService;
use Illuminate\Database\Eloquent\Collection;

class OrderDetailService
{
    /**
     * @param Order $order
     * @param Collection<Product> $cartProducts
     * @return void
     */
    public function createOrderDetails(Order $order, Collection $cartProducts): void
    {
        $productService = new ProductService();

        $cartProducts->map(function ($product) use ($order, $productService) {
            // Deduct stock
            $productService->updateStock($product->id, $product->quantity, false);

            // Create new OrderDetail
            return $order->orderDetails()->create([
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_price' => $product->price,
                'quantity' => $product->quantity,
            ]);
        });
    }
}
