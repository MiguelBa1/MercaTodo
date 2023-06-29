<?php

namespace App\Http\Controllers\Web\Cart;

use App\Http\Controllers\Controller;
use App\Services\Cart\CartService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render(
            'Cart/Index',
            [
                "cartProducts" => fn () => (new CartService())->getProductsWithDetails($request->user()->id),
            ]
        );
    }
}
