<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\Payment\PaymentService;
use App\Services\ProductService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(
        Request $request,
        CartService $cartService,
        ProductService $productService,
        OrderService $orderService,
        PaymentService $paymentService
    ): JsonResponse {
        $cartProducts = $cartService->getProductsWithDetails($request->user()->id);

        if (empty($cartProducts)) {
            return response()->json([
                'message' => 'Cart is empty'
            ], 400);
        }

        foreach ($cartProducts as $cartProduct) {
            if (!$productService->verifyProductAvailability($cartProduct, $cartProduct['quantity'])) {
                return response()->json([
                    'message' => 'Product ' . $cartProduct['name'] . ' is not available, please remove it from cart'
                ], 400);
            }
        }

        $order = $orderService->createOrder(
            $request->user(),
            collect($cartProducts)
        );

        try {
            $redirectUrl = $paymentService->processPayment(
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
