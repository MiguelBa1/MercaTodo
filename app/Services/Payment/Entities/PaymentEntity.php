<?php

namespace App\Services\Payment\Entities;

use App\Models\Order;
use Illuminate\Contracts\Support\Arrayable;

class PaymentEntity implements Arrayable
{
    private Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function toArray(): array
    {
        return [
            'reference' => $this->order->reference,
            'description' => date($this->order->created_at),
            'amount' => [
                'currency' => 'USD',
                'total' => $this->order->total,
            ],
            'items' => $this->getItems(),
        ];
    }

    private function getItems(): array
    {
        $items = [];
        $orderDetails = $this->order->orderDetails->load('product');

        foreach ($orderDetails as $orderDetail) {
            $items[] = [
                'sku' => $orderDetail->product->sku,
                'name' => $orderDetail->product->name,
                'qty' => $orderDetail->quantity,
                'price' => $orderDetail->product_price,
            ];
        }

        return $items;
    }
}
