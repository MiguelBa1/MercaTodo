<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class CartValidationException extends Exception
{
    protected $code = 422;

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage()
        ], $this->getCode());
    }

    public static function empty(): self
    {
        return new self(__('validation.custom.cart.empty'));
    }
}
