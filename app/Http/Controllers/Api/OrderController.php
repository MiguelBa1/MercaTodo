<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request, OrderService $orderService): JsonResponse
    {
        $orders = $orderService->getOrders($request->user()->id);

        return response()->json([
            'orders' => $orders
        ]);
    }

    public function store(Request $request, CartService $cartService, OrderService $orderService): JsonResponse
    {
        $cart = $cartService->getProducts($request->user()->id);

        if (empty($cart)) {
            return response()->json([
                'message' => 'Cart is empty'
            ], 400);
        }

        $orderService->createOrder(
            $request->user(),
            collect($cart),
            $cartService->getTotal($cart)
        );

        $cartService->clear($request->user()->id);

        return response()->json([
            'message' => 'Order created successfully'
        ], 201);
    }
}
