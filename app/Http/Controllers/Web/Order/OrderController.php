<?php

namespace App\Http\Controllers\Web\Order;

use App\Http\Controllers\Controller;
use App\Services\Order\OrderService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function index(Request $request, OrderService $orderService): Response
    {
        $orders = $orderService->getOrders($request->user()->id);

        return Inertia::render('Orders/Index', [
            'orders' => $orders
        ]);
    }
}
