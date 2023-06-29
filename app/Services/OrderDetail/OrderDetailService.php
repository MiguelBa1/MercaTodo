<?php

namespace App\Services\OrderDetail;

use App\Models\Order;
use App\Services\Product\ProductService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OrderDetailService
{
    public function createOrderDetails(Order $order, array $productData): Collection
    {
        $orderDetails = collect();
        $productService = new ProductService();

        DB::transaction(function () use ($productService, $order, $productData, $orderDetails) {
            foreach ($productData as $product) {
                $orderDetail = $order->orderDetails()->create([
                    'product_id' => $product['id'],
                    'product_name' => $product['name'],
                    'product_price' => $product['price'],
                    'quantity' => $product['quantity'],
                ]);

                $productService->updateStock($product['id'], $product['quantity'], false);

                $orderDetails->push($orderDetail);
            }
        });

        return $orderDetails;
    }
}
