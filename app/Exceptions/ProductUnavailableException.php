<?php

namespace App\Exceptions;

use App\Models\Product;
use Exception;
use Illuminate\Http\JsonResponse;

class ProductUnavailableException extends Exception
{
    protected Product $product;

    public function __construct(Product $product, $message = '', $code = 0, Exception $previous = null)
    {
        $this->product = $product;

        parent::__construct($message, $code, $previous);
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], 400);
    }
}
