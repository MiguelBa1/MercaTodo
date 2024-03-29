<?php

namespace App\Http\Controllers\Api\Admin\Product;

use App\Exceptions\ProductsExportException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Products\ProductsExportRequest;
use App\Services\Product\ProductExportService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductExportController extends Controller
{
    public function export(ProductsExportRequest $request): JsonResponse
    {
        $from = (int)$request->query('from');
        $to = (int)$request->query('to');

        $fileName = (new ProductExportService())->exportProducts($from, $to);

        return response()->json(['filename' => $fileName]);
    }

    /**
     * @throws ProductsExportException
     */
    public function download(string $fileName): StreamedResponse
    {
        return (new ProductExportService())->download($fileName);
    }

    /**
     * @throws ProductsExportException
     */
    public function checkExport(string $fileName): JsonResponse
    {
        $status = (new ProductExportService())->checkExportStatus($fileName);

        return response()->json(['status' => $status]);
    }
}
