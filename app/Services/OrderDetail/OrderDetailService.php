<?php

namespace App\Services\OrderDetail;

use App\Models\Order;
use App\Services\Product\ProductService;
use Illuminate\Support\Collection;

class OrderDetailService
{
    /**
     * @param Order $order
     * @param Collection $cartItems
     * @return void
     */
    public function createOrderDetails(Order $order, Collection $cartItems): void
    {
        $productService = new ProductService();

        foreach ($cartItems as $item) {
            $product = $item['product'];
            $quantity = $item['quantity'];

            // Deduct stock
            $productService->updateStock($product, $quantity, false);

            // Create new OrderDetail
            $order->orderDetails()->create([
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_price' => $product->price,
                'quantity' => $quantity,
            ]);
        }
    }
}
