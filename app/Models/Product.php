<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'name',
        'description',
        'price',
        'image',
        'stock',
        'status',
        'brand_id',
        'category_id',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getStatusAttribute($value): string
    {
        return $value ? 'Active' : 'Inactive';
    }


    /**
     * @param $query
     * @return Builder
     */
    public function scopeIndex($query): Builder
    {
        return $query->join('categories', 'categories.id', '=', 'products.category_id')
            ->join('brands', 'brands.id', '=', 'products.brand_id')
            ->select(
                'products.id',
                'products.sku',
                'products.name',
                'products.description',
                'products.price',
                'products.image',
                'products.stock',
                'products.status',
                'categories.name as category_name',
                'brands.name as brand_name'
            )
            ->orderBy('products.id', 'desc');
    }
}
