<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class OrderService
{
    public function createOrder(User $user, Collection $cart, float $total): Model
    {
        $order = $user->orders()->create([
            'reference' => crc32(uniqid()),
            'user_id' => $user->getAttribute('id'),
            'total' => $total
        ]);

        $order->orderDetails()->createMany(
            $cart->map(function ($quantity, $product_id) {
                $product = Product::query()->findOrFail($product_id);
                $product->setAttribute('stock', $product->getAttribute('stock') - $quantity);
                $product->save();
                return [
                    'product_id' => $product_id,
                    'quantity' => $quantity,
                    'product_name' => $product->getAttribute('name'),
                    'product_price' => $product->getAttribute('price'),
                ];
            })->toArray()
        );

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
