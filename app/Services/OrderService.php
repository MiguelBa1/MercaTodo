<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class OrderService
{
    public function createOrder(User $user, Collection $cartProducts): Model|Order
    {
        $total = $cartProducts->sum(function ($product) {
            return $product['price'] * $product['quantity'];
        });

        $order = $user->orders()->create([
            'reference' => crc32(uniqid()),
            'user_id' => $user->getAttribute('id'),
            'total' => $total
        ]);

        // create order details and update product stock
        $cartProducts->each(function ($product) use ($order) {
            $order->orderDetails()->create([
                'product_id' => $product['id'],
                'product_name' => $product['name'],
                'product_price' => $product['price'],
                'quantity' => $product['quantity'],
            ]);

            $productModel = Product::query()->find($product['id']);
            $productModel->setAttribute('stock', $productModel->getAttribute('stock') - $product['quantity']);
            $productModel->save();
        });

        return $order;
    }

    public function getOrders(int $user_id): collection
    {
        return Order::query()
            ->latest()
            ->select('id', 'reference', 'status', 'total', 'created_at')
            ->where('user_id', $user_id)
            ->with('orderDetails:id,product_id,order_id,product_name,product_price,quantity')
            ->get();
    }
}
