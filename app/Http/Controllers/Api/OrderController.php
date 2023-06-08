<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\Payment\PaymentService;
use Exception;
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

    public function store(Request $request, CartService $cartService, OrderService $orderService, PaymentService $paymentService): JsonResponse
    {
        $cartProducts = $cartService->getProductsWithDetails($request->user()->id);

        if (empty($cartProducts)) {
            return response()->json([
                'message' => 'Cart is empty'
            ], 400);
        }

        $order = $orderService->createOrder(
            $request->user(),
            collect($cartProducts)
        );

        try {
            $redirectUrl = $paymentService->pay(
                $order,
                $request->ip(),
                $request->userAgent()
            );

            $cartService->clear($request->user()->id);

            return response()->json([
                'redirect_url' => $redirectUrl
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
