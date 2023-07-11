<?php

namespace App\Services\Product;

use App\Enums\ExportStatusEnum;
use App\Exports\ProductsExport;
use App\Models\Export;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exceptions\ProductsExportException;
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

    /**
     * @throws ProductsExportException
     */
    public function checkExportStatus(string $fileName): string
    {
        $export = Export::query()->where('filename', $fileName)->first();

        if ($export == null) {
            throw ProductsExportException::recordNotFoundError();
        }

        if ($export->status == ExportStatusEnum::FAILED->value) {
            throw ProductsExportException::exportFailedError();
        }

        if ($export->status == ExportStatusEnum::READY->value) {
            return ExportStatusEnum::READY->value;
        }

        return ExportStatusEnum::PENDING->value;
    }

    /**
     * @throws ProductsExportException
     */
    public function download(string $fileName): StreamedResponse
    {
        $filePath = 'exports/' . $fileName;

        if (!Storage::exists($filePath)) {
            throw ProductsExportException::fileNotFoundError();
        }

        return Storage::download($filePath);
    }
}
