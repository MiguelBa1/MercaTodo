<?php

namespace App\Services\Product;

use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;
use App\Enums\ImportStatusEnum;
use App\Exceptions\ProductsImportException;
use App\Models\Import;

class ProductImportService
{
    public function import(UploadedFile $file, int $userId): array
    {
        /** @var Import $import */
        $import = Import::query()->firstOrCreate([
            'user_id' => $userId,
            'filename' => $file->getClientOriginalName(),
        ]);

        $import->update([
            'status' => ImportStatusEnum::PENDING,
            'errors' => [],
        ]);

        Excel::queueImport(new ProductsImport($import), $file);

        return ['status' => 'import queued'];
    }

    /**
     * @throws ProductsImportException
     */
    public function checkImport(string $fileName): array
    {
        /** @var Import $import */
        $import = Import::query()->where('filename', $fileName)->firstOrFail();

        if ($import->status === ImportStatusEnum::FAILED) {
            throw ProductsImportException::importFailedError();
        }

        if ($import->status === ImportStatusEnum::HAS_ERRORS) {
            return [
                'status' => $import->status,
                'errors' => $import->errors,
            ];
        }

        if ($import->status === ImportStatusEnum::READY) {
            return [
                'status' => $import->status,
            ];
        }

        return [
            'status' => ImportStatusEnum::PENDING,
        ];
    }
}
