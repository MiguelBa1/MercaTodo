<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromQuery, WithHeadings, ShouldQueue
{
    private int $from;
    private int $to;

    public function __construct(int $from = null, int $to = null)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function query(): Relation|Builder|\Illuminate\Database\Query\Builder
    {
        $query = Product::query()
            ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
            ->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
            ->select(
                'products.id',
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
            'ID',
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
}
