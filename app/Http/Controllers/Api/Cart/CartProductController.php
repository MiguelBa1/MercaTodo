<?php

namespace App\Http\Controllers\Api\Cart;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartProductController extends Controller
{
    public function index(Request $request, CartService $cartService): array
    {
        return $cartService->getProductsWithDetails($request->user()->id);
    }
}
