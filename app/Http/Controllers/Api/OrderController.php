<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $orders = Order::query()
            ->select('id', 'reference', 'status', 'total', 'created_at')
            ->where('user_id', $request->user()->id)
            ->with('orderDetails:id,product_id,order_id,product_name,product_price,quantity')
            ->get();
        return response()->json([
            'orders' => $orders
        ]);
    }

    public function store(Request $request, CartService $cartService): JsonResponse
    {
        $cart = $cartService->getProducts($request->user()->id);

        if (empty($cart)) {
            return response()->json([
                'message' => 'Cart is empty'
            ], 400);
        }

        $order = $request->user()->orders()->create([
            'reference' => uniqid(),
            'user_id' => $request->user()->id,
            'total' => $cartService->getTotal($cart)
        ]);

        $order->orderDetails()->createMany(
            collect($cart)->map(function ($quantity, $product_id) use ($cartService) {
                $product = Product::query()->find($product_id);
                return [
                    'product_id' => $product_id,
                    'quantity' => $quantity,
                    'product_name' => $product->getAttribute('name'),
                    'product_price' => $product->getAttribute('price'),
                ];
            })->toArray()
        );

        $cartService->clear($request->user()->id);

        return response()->json([
            'message' => 'Order created successfully'
        ], 201);
    }
}
