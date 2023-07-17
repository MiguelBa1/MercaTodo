<?php

namespace App\Imports;

use App\Enums\ImportStatusEnum;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Import;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Validators\Failure;

class ProductsImport implements
    ToModel,
    WithBatchInserts,
    WithHeadingRow,
    WithUpserts,
    ShouldQueue,
    WithChunkReading,
    SkipsOnFailure,
    WithValidation,
    WithEvents,
    SkipsEmptyRows
{
    public function __construct(
        private Import $import,
    ) {
    }

    /**
     * @param array $row
     *
     * @return Model|Product|null
     */
    public function model(array $row): Model|Product|null
    {
        /** @var Brand $brand */
        $brand = Brand::query()->firstOrCreate([
            'name' => $row['brand'],
        ]);
        /** @var Category $category */
        $category = Category::query()->firstOrCreate([
            'name' => $row['category'],
        ]);

        return new Product([
            'sku' => $row['sku'],
            'name' => $row['name'],
            'description' => $row['description'],
            'price' => $row['price'],
            'stock' => $row['stock'],
            'status' => strtolower($row['status']) === 'active',
            'brand_id' => $brand->id,
            'category_id' => $category->id,
        ]);
    }

    public function uniqueBy(): string
    {
        return 'sku';
    }

    public function rules(): array
    {
        return [
            'sku' => 'required|numeric',
            'name' => 'required|string|min:3|max:100',
            'description' => 'required|string|min:3|max:500',
            'price' => 'required|numeric|min:0|max:100000',
            'stock' => 'required|numeric|min:0|max:1000',
            'status' => 'required|string|in:Active,Inactive',
            'brand' => 'required|string|min:2|max:100',
            'category' => 'required|string|min:2|max:100',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterImport::class => function (AfterImport $event) {
                $this->import->refresh();
                if (!empty($this->import->errors)) {
                    $this->import->update([
                        'status' => ImportStatusEnum::HAS_ERRORS,
                    ]);
                } else {
                    $this->import->update([
                        'status' => ImportStatusEnum::READY,
                    ]);
                }
            },
        ];
    }

    public function onFailure(Failure ...$failures): void
    {
        $this->import->refresh();
        $errors = [];

        foreach ($failures as $failure) {
            if (isset($errors[$failure->row()])) {
                $errors[$failure->row()] = array_merge($errors[$failure->row()], $failure->errors());
            } else {
                $errors[$failure->row()] = $failure->errors();
            }
        }

        $this->import->errors += $errors;
        $this->import->save();
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function failed(\Throwable $e): void
    {
        $this->import->status = ImportStatusEnum::FAILED;

        Log::error('Import failed', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'trace' => $e->getTraceAsString(),
        ]);
    }
}
