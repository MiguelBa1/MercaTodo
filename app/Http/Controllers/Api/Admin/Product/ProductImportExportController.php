<?php

namespace App\Http\Controllers\Api\Admin\Product;

use App\Exports\ProductsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Products\ProductsExportRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductImportExportController extends Controller
{
    public function export(ProductsExportRequest $request): JsonResponse
    {
        $from = (int)$request->query('from');
        $to = (int)$request->query('to');

        $fileName = 'products-' . date('Y-m-d-H-i-s') . '.xlsx';

        Excel::queue(new ProductsExport($from, $to), 'exports/' . $fileName);

        return response()->json(['filename' => $fileName]);
    }

    public function download(string $fileName): StreamedResponse
    {
        $filePath = 'exports/' . $fileName;

        if (!Storage::exists($filePath)) {
            abort(404);
        }

        return Storage::download($filePath);
    }

    public function checkExport(string $fileName): JsonResponse
    {
        $filePath = 'exports/' . $fileName;

        if (Storage::exists($filePath)) {
            return response()->json(['status' => 'ready']);
        }

        return response()->json(['status' => 'pending']);
    }
}
