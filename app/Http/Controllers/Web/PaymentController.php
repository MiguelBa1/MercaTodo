<?php

namespace App\Http\Controllers\Web;

use App\Enums\OrderStatusEnum;
use App\Exceptions\ProductUnavailableException;
use App\Http\Controllers\Controller;
use App\Services\Payment\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class PaymentController extends Controller
{
    public function handleRedirect(Request $request, Order $order, PaymentService $paymentService): RedirectResponse|Response
    {
        if ($order->status !== OrderStatusEnum::PENDING || $order->user_id !== $request->user()->id) {
            return Redirect::to(route('home'));
        }

        $updatedOrder = $paymentService->handlePaymentResponse($order);
        $status = $updatedOrder->status;

        $viewName = match ($status) {
            OrderStatusEnum::COMPLETED => 'Payment/Completed',
            OrderStatusEnum::REJECTED => 'Payment/Rejected',
            default => 'Payment/Pending',
        };

        return Inertia::render($viewName, [
            'order' => $updatedOrder->only(['reference', 'total', 'status', 'created_at'])
        ]);
    }

    public function handleCanceled(Request $request, Order $order, PaymentService $paymentService): RedirectResponse|Response
    {
        if ($order->status !== OrderStatusEnum::PENDING) {
            return Redirect::to(route('home'));
        }

        if ($order->user_id !== $request->user()->id) {
            return Redirect::to(route('home'));
        }

        $paymentService->handlePaymentResponse($order);
        return Inertia::render('Payment/Canceled');
    }

    public function retryPayment(Request $request, Order $order, PaymentService $paymentService): RedirectResponse|JsonResponse
    {
        if ($order->user_id !== $request->user()->id || $order->status === OrderStatusEnum::COMPLETED) {
            return Redirect::to(route('home'));
        }

        if ($order->status === OrderStatusEnum::PENDING) {
            return response()->json([
                'redirect_url' => $order->process_url
            ], 201);
        }

        if ($order->status === OrderStatusEnum::REJECTED) {
            try {
                $redirectUrl = $paymentService->retryPayment($order, $request->ip(), $request->userAgent());

                return response()->json([
                    'redirect_url' => $redirectUrl
                ], 201);
            } catch (ProductUnavailableException $e) {
                return $e->render();
            } catch (\Exception $e) {
                return response()->json([
                    'message' => $e->getMessage()
                ], 500);
            }
        }

        return Redirect::to(route('home'));
    }
}
