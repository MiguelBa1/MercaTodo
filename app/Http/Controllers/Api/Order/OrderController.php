<?php

namespace App\Http\Controllers\Api\Order;

use App\Exceptions\CartValidationException;
use App\Exceptions\ProcessPaymentException;
use App\Exceptions\ProductUnavailableException;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Cart\CartService;
use App\Services\Order\OrderService;
use App\Services\Payment\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * @throws CartValidationException
     * @throws ProcessPaymentException
     * @throws ProductUnavailableException
     */
    public function store(Request $request): JsonResponse
    {
        $cartService = new CartService();

        $cartItems = $cartService->validatedCart($request->user()->id);

        /** @var Order $order */
        $order = (new OrderService())->createOrder(
            $request->user(),
            $cartItems
        );

        $redirectUrl = (new PaymentService())->processPayment($order, $request->ip(), $request->userAgent());

        $cartService->clear($request->user()->id);

        return response()->json([
            'redirect_url' => $redirectUrl
        ], 201);
    }
}
