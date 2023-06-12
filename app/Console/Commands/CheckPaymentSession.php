<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Services\Payment\PaymentService;
use Illuminate\Database\Eloquent\Collection;

class CheckPaymentSession extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-payment-session';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        /** @var Collection<Order> $orders */
        $orders = Order::query()->where('status', '=', 'PENDING')->get();

        $paymentService = new PaymentService();

        foreach ($orders as $order) {
            $paymentService->handlePaymentResponse($order);
        }
    }
}
