<?php

namespace App\Http\Controllers\Api\Cart;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartStoreRequest;
use App\Services\Cart\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function store(CartStoreRequest $request): void
    {
        $this->cartService->addProduct(
            $request->user()->id,
            $request->input('product_id'),
            $request->input('quantity')
        );
    }

    public function destroy(Request $request, int $product_id): void
    {
        $this->cartService->removeProduct(
            $request->user()->id,
            $product_id
        );
    }

    public function index(Request $request): array
    {
        return $this->cartService->getCart($request->user()->id);
    }
}
