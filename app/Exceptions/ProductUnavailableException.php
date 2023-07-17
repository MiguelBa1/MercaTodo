<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ProductUnavailableException extends Exception
{
    protected $code = 422;

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage()
        ], $this->getCode());
    }

    public static function unavailable(string $product_name): self
    {
        return new self(__('validation.custom.product.unavailable', [
            'product_name' => $product_name
        ]));
    }
}
