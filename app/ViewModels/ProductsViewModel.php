<?php

namespace App\ViewModels;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\ViewModels\ViewModel;

class ProductsViewModel extends ViewModel
{
    public ?int $category_id;
    public ?int $brand_id;
    public ?string $searchQuery;
    /**
     * @var Collection|Product[]
     */
    public $products;
    /**
     * @var Collection|Category[]
     */
    public $categories;
    /**
     * @var Collection|Brand[]
     */
    public $brands;

    public function __construct(int $category_id = null, int $brand_id = null, string $searchQuery = null)
    {
        $this->category_id = $category_id;
        $this->brand_id = $brand_id;
        $this->searchQuery = $searchQuery;
    }

    public function products(): LengthAwarePaginator
    {
        $query = Product::query()
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->select(
            'products.id',
            'products.sku',
            'products.name',
            'products.price',
            'products.image',
            'categories.name as category_name',
        );

        if ($this->category_id) {
            $query->where('category_id', $this->category_id);
        }

        if ($this->brand_id) {
            $query->where('brand_id', $this->brand_id);
        }

        if ($this->searchQuery !== null) {
            $query->where('products.name', 'like', "%{$this->searchQuery}%");
        }

        $this->products = $query
            ->where('status', true)
            ->orderBy('id', 'desc')->paginate(10);

        return $this->products;
    }

    public function categories() : Collection
    {
        $this->categories = Category::all();

        return $this->categories;
    }

    public function brands() : Collection
    {
        $this->brands = Brand::all();

        return $this->brands;
    }
}
