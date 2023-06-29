<?php

namespace App\Services\Payment;

use App\Enums\OrderStatusEnum;
use App\Enums\TransactionStatusEnum;
use App\Exceptions\ProcessPaymentException;
use App\Exceptions\ProductUnavailableException;
use App\Models\Order;
use App\Models\Product;
use App\Services\Order\OrderService;
use App\Services\Payment\Entities\AuthEntity;
use App\Services\Payment\Entities\BuyerEntity;
use App\Services\Payment\Entities\PaymentEntity;
use App\Services\Product\ProductService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class PaymentService
{
    /**
     * @throws ProcessPaymentException
     */
    public function processPayment(Order $order, string $ipAddress, string $userAgent): string
    {
        $response = Http::post(
            config('placetopay.url') . '/api/session',
            $this->preparePaymentSession($order, $ipAddress, $userAgent)
        );

        if ($response->ok()) {
            $order->update([
                'request_id' => $response->json()['requestId'],
                'process_url' => $response->json()['processUrl'],
            ]);

            return $response->json()['processUrl'];
        } else {
            $orderService = new OrderService();

            $orderService->deleteOrder($order);

            throw new ProcessPaymentException('Error creating payment session');
        }
    }

    private function preparePaymentSession(Order $order, string $ipAddress, string $userAgent): array
    {
        $auth = new AuthEntity();
        $buyer = new BuyerEntity($order->user);
        $payment = new PaymentEntity($order);

        return [
            'locale' => 'en_US',
            'auth' => $auth->toArray(),
            'buyer' => $buyer->toArray(),
            'payment' => $payment->toArray(),
            'expiration' => Carbon::now()->addMinutes(6),
            'returnUrl' => route('payment.result', $order->id),
            'cancelUrl' => route('payment.canceled', $order->id),
            'ipAddress' => $ipAddress,
            'userAgent' => $userAgent,
        ];
    }

    public function handlePaymentResponse(Order $order): Order
    {
        $auth = new AuthEntity();
        $response = Http::post(
            config('placetopay.url') . "/api/session/{$order->request_id}",
            ['auth' => $auth->toArray()]
        );

        if ($response->ok()) {
            $this->updateOrderStatus($order, $response->json()['status']['status']);
        }

        return $order;
    }

    private function updateOrderStatus(Order $order, string $status): void
    {
        $orderService = new OrderService();

        switch ($status) {
            case TransactionStatusEnum::APPROVED->value:
                $orderService->completeOrder($order);
                break;
            case TransactionStatusEnum::REJECTED->value:
                $orderService->rejectOrder($order);
                break;
        }
    }

    /**
     * @throws ProductUnavailableException
     * @throws ProcessPaymentException
     */
    public function retryPayment(Order $order, string $ipAddress, string $userAgent): string
    {
        $productService = new ProductService();

        foreach ($order->orderDetails as $orderDetail) {
            /** @var Product $product */
            $product = Product::query()->find($orderDetail->product_id);

            if (!$productService->verifyProductAvailability($product, $orderDetail->quantity)) {
                throw ProductUnavailableException::unavailable($product->name);
            }

            $productService->updateStock($product->id, $orderDetail->quantity);
        }

        $processUrl = $this->processPayment($order, $ipAddress, $userAgent);

        $order->status = OrderStatusEnum::PENDING;
        $order->save();

        return $processUrl;
    }
}
