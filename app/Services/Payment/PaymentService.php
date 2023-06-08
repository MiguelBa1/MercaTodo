<?php

namespace App\Services\Payment;

use App\Models\Order;
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
            $order->delete();

            throw new Exception($response->json()['message']);
        }
    }

    private function createSession(Order $order, string $ipAddress, string $userAgent): array
    {
        $auth = new AuthEntity();
        $buyer = new BuyerEntity($order->user);
        $payment = new PaymentEntity($order);

        return [
            'auth' => $auth->toArray(),
            'buyer' => $buyer->toArray(),
            'payment' => $payment->toArray(),
            'expiration' => Carbon::now()->addHour(),
            'returnUrl' => route('order.index'),
            'ipAddress' => $ipAddress,
            'userAgent' => $userAgent,
        ];
    }
}
