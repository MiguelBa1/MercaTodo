<?php

namespace App\Services\Order;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\OrderDetail\OrderDetailService;
use App\Services\Product\ProductService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class OrderService
{
    public function createOrder(User $user, array $cartProducts): Order|Model
    {
        $total = 0;

        foreach ($cartProducts as $cartProduct) {
            $total += $cartProduct['price'] * $cartProduct['quantity'];
        }

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
            ->with('orderDetails:id,order_id,product_name,product_price,quantity')
            ->latest()
            ->select('id', 'reference', 'process_url', 'status', 'total', 'created_at')
            ->where('user_id', $user_id)
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
