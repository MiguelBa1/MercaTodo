<?php

namespace App\Services\Product;

use App\Enums\ExportStatusEnum;
use App\Exports\ProductsExport;
use App\Models\Export;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductExportService
{
    public function exportProducts(int $from, int $to): string
    {
        $fileName = 'products-' . date('Y-m-d-H-i-s') . '.xlsx';

        $export = new Export([
            'filename' => $fileName,
            'status' => ExportStatusEnum::PENDING->value,
            'type' => 'products',
        ]);

        $export->save();

        Excel::queue(new ProductsExport($from, $to, $export), 'exports/' . $fileName);

        return $fileName;
    }

    public function checkExportStatus(string $fileName): string
    {
        $filePath = 'exports/' . $fileName;

        if (Storage::exists($filePath)) {
            return ExportStatusEnum::READY->value;
        }

        return ExportStatusEnum::PENDING->value;
    }

    public function download(string $fileName): StreamedResponse
    {
        $filePath = 'exports/' . $fileName;

        if (!Storage::exists($filePath)) {
            abort(404);
        }

        return Storage::download($filePath);
    }
}
