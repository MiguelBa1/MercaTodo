<?php

namespace App\Exports;

use App\Enums\ExportStatusEnum;
use App\Models\Export;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class ProductsExport implements FromQuery, WithHeadings, ShouldQueue, WithEvents
{
    private int $from;
    private int $to;
    private Export $export;

    public function __construct(int $from, int $to, Export $export)
    {
        $this->from = $from;
        $this->to = $to;
        $this->export = $export;
    }

    public function query(): Relation|Builder|\Illuminate\Database\Query\Builder
    {
        $query = Product::query()
            ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
            ->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
            ->select(
                'products.sku',
                'products.name',
                'products.description',
                'products.price',
                'products.stock',
                'products.status',
                'categories.name as category',
                'brands.name as brand'
            )
            ->orderByDesc('products.id');

        if (isset($this->from) && isset($this->to)) {
            $query->whereBetween('products.id', [$this->from, $this->to]);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'SKU',
            'Name',
            'Description',
            'Price',
            'Stock',
            'Status',
            'Category',
            'Brand',
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $this->export->status = ExportStatusEnum::READY;
                $this->export->save();
            },
        ];
    }

    public function failed(\Throwable $e): void
    {
        $this->export->status = ExportStatusEnum::FAILED;
        $this->export->error = $e->getMessage();
        $this->export->save();

        Log::error('Export failed', [
            'export_id' => $this->export->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
    }
}
