<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ProcessPaymentException extends Exception
{
    protected $code = 503;

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], $this->code);
    }

    public static function sessionError(): self
    {
        return new self(__('validation.custom.payment.session_error'));
    }

    public static function invalidRequestError(): self
    {
        return new self(__('validation.custom.payment.authentication_error'));
    }

    public static function unexpectedError(): self
    {
        return new self(__('validation.custom.payment.unexpected_error'));
    }
}
