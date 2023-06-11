<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\Inertia;

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
