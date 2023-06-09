<?php

namespace App\Services\Payment;

use App\Enums\TransactionStatusEnum;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\Payment\Entities\AuthEntity;
use App\Services\Payment\Entities\BuyerEntity;
use App\Services\Payment\Entities\PaymentEntity;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;

class PaymentService
{
    /**
     * @throws Exception
     */
    public function pay(Order $order, string $ipAddress, string $userAgent): string
    {
        $response = Http::post(
            config('placetopay.url') . '/api/session',
            $this->createSession($order, $ipAddress, $userAgent)
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

            throw new Exception($response->json()['message']);
        }
    }

    private function createSession(Order $order, string $ipAddress, string $userAgent): array
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
            'returnUrl' => route('payment.result'),
            'cancelUrl' => route('payment.canceled'),
            'ipAddress' => $ipAddress,
            'userAgent' => $userAgent,
        ];
    }

    public function processResponse(Order $order): void
    {
        $orderService = new OrderService();

        $auth = new AuthEntity();
        $response = Http::post(
            config('placetopay.url') . "/api/session/{$order->request_id}",
            ['auth' => $auth->toArray()]
        );

        if ($response->ok()) {
            $status = $response->json()['status']['status'];

            switch ($status) {
                case TransactionStatusEnum::APPROVED->value:
                    $orderService->completeOrder($order);
                    break;
                case TransactionStatusEnum::REJECTED->value:
                    $orderService->rejectOrder($order);
                    break;
            }
        }
    }
}
