<?php

namespace App\Http\Controllers\Api\Admin\Product;

use App\Exceptions\ProductsImportException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Products\ProductImportRequest;
use App\Services\Product\ProductImportService;
use Illuminate\Http\JsonResponse;

class ProductImportController extends Controller
{
    public function import(ProductImportRequest $request): JsonResponse
    {
        $response = (new ProductImportService())->import($request->file('file'), auth()->id());

        return response()->json($response);
    }

    /**
     * @throws ProductsImportException
     */
    public function checkImport(string $fileName): array
    {
        return (new ProductImportService())->checkImport($fileName);
    }
}
