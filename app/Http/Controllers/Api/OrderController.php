<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'orders' => $request->user()->orders()->with('orderDetails')->get()
        ]);
    }

    public function show(Request $request, Order $order): JsonResponse
    {
        if ($request->user()->id !== $order->getAttribute('user_id')) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        return response()->json([
            'order' => $order->load('orderDetails')
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
            'user_id' => $request->user()->id,
            'total' => $cartService->getTotal($cart)
        ]);

        $order->orderDetails()->createMany(
            collect($cart)->map(function ($quantity, $product_id) use ($cartService) {
                return [
                    'product_id' => $product_id,
                    'quantity' => $quantity,
                    'price' => $cartService->getPrice($product_id)
                ];
            })->toArray()
        );

        $cartService->clear($request->user()->id);

        return response()->json([
            'order' => $order->load('orderDetails')
        ], 201);
    }
}
