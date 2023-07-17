<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ProductsExportException extends Exception
{
    protected $code = 500;

    public function render(): JsonResponse
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], $this->getCode());
    }

    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    public static function fileNotFoundError(): self
    {
        return (new self(__('validation.custom.export.file_not_found_error')))->setCode(404);
    }

    public static function recordNotFoundError(): self
    {
        return (new self(__('validation.custom.export.record_not_found_error')))->setCode(404);
    }

    public static function exportFailedError(): self
    {
        return (new self(__('validation.custom.export.export_failed_error')))->setCode(500);
    }
}
