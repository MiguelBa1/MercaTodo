<?php

namespace App\Services;

use App\Enums\OrderStatusEnum;
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

    public function deleteOrder(Order $order): bool
    {
        foreach ($order->orderDetails as $orderDetail) {
            /* @var Product $product */
            $product = Product::query()->find($orderDetail->product_id);
            $product->stock = $product->stock + $orderDetail->quantity;

            if ($product->stock > 0) {
                $product->status = 1;
            }

            $product->save();

            $orderDetail->delete();
        }

        return $order->delete();
    }

    public function completeOrder(Order $order): void
    {
        $order->status = OrderStatusEnum::COMPLETED;
        $order->save();
    }

    public function rejectOrder(Order $order): void
    {
        $order->status = OrderStatusEnum::REJECTED;
        $order->save();

        foreach ($order->orderDetails as $orderDetail) {
            /* @var Product $product */
            $product = Product::query()->find($orderDetail->product_id);
            $product->stock += $orderDetail->quantity;

            if ($product->stock > 0) {
                $product->status = 1;
            }

            $product->save();
        }
    }
}
