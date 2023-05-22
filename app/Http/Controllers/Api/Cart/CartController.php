<?php

namespace App\Http\Controllers\Api\Cart;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartRequest;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function addProduct(CartRequest $request): void
    {
        $this->cartService->addProduct(
            $request->user()->id,
            $request->input('product_id'),
            $request->input('quantity')
        );
    }

    public function removeProduct(Request $request): void
    {
        $this->cartService->removeProduct(
            $request->user()->id,
            $request->get('product_id')
        );
    }

    public function getProducts(Request $request): array
    {
        return $this->cartService->getProducts($request->user()->id);
    }

    public function clearCart(Request $request): void
    {
        $this->cartService->clearCart($request->user()->id);
    }
}
