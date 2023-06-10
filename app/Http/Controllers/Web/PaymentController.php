<?php

namespace App\Http\Controllers\Web;

use App\Enums\OrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Services\Payment\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class PaymentController extends Controller
{
    public function handleRedirect(Request $request, PaymentService $paymentService): RedirectResponse|Response
    {
        /* @var ?Order $order */
        $order= Order::query()
            ->where('user_id', $request->user()->id)
            ->where('status', OrderStatusEnum::PENDING)
            ->latest()
            ->first();

        if (!$order) {
            return Redirect::to(route('home'));
        }

        try {
            $paymentService->handlePaymentResponse($order);

            return Inertia::render('Payment/Result', [
                'order' => $order->only('reference', 'total', 'created_at')
            ]);
        } catch (\Exception $e) {
            return Inertia::render('Payment/Result', [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function handleCanceled(Request $request, PaymentService $paymentService): RedirectResponse|Response
    {
        /* @var ?Order $pendingOrder */
        $pendingOrder = Order::query()
            ->where('user_id', $request->user()->id)
            ->where('status', OrderStatusEnum::PENDING)
            ->latest()
            ->first();

        if (!$pendingOrder) {
            return Redirect::to(route('home'));
        }

        try {
            $paymentService->handlePaymentResponse($pendingOrder);
            return Inertia::render('Payment/Canceled');
        } catch (\Exception $e) {
            return Inertia::render('Payment/Canceled', [
                'error' => $e->getMessage()
            ]);
        }
    }
}
