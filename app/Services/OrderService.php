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
    public function createOrder(User $user, Collection $cartProducts): Order|Model
    {
        $total = $cartProducts->sum(function ($product) {
            return $product['price'] * $product['quantity'];
        });

        /** @var Order $order */
        $order = $user->orders()->create([
            'reference' => crc32(uniqid()),
            'user_id' => $user->getAttribute('id'),
            'total' => $total
        ]);

        $orderDetailService = new OrderDetailService();
        $orderDetailService->createOrderDetails($order, $cartProducts);

        return $order;
    }

    public function getOrders(int $user_id): collection
    {
        return Order::query()
            ->latest()
            ->select('id', 'reference', 'process_url', 'status', 'total', 'created_at')
            ->where('user_id', $user_id)
            ->with('orderDetails:id,order_id,product_name,product_price,quantity')
            ->get();
    }

    public function deleteOrder(Order $order): bool
    {
        $productService = new ProductService();
        foreach ($order->orderDetails as $orderDetail) {
            /** @var Product $product */
            $product = Product::query()->find($orderDetail->product_id);
            $productService->updateStock($product->id, $orderDetail->quantity, true);

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

        $productService = new ProductService();

        foreach ($order->orderDetails as $orderDetail) {
            /** @var Product $product */
            $product = Product::query()->find($orderDetail->product_id);
            $productService->updateStock($product->id, $orderDetail->quantity, true);
        }
    }
}
