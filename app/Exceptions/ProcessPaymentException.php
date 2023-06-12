<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ProcessPaymentException extends Exception
{
    public function __construct(string $message = "", int $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], 400);
    }
}
