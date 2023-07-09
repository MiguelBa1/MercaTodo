<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ProductsImportException extends Exception
{
    protected $code = 500;

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], $this->getCode());
    }

    public static function importFailedError(): self
    {
        return (new self(__('validation.custom.import.import_failed_error')));
    }
}
