<?php

namespace App\Services;

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
                $product = Product::query()->find($product_id);
                $product->decrement('stock', $quantity);
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
}
