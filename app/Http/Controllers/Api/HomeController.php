<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\HomeRequest;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class HomeController
{
    public function index(HomeRequest $request): LengthAwarePaginator
    {
        $query = Product::query()->join('categories', 'categories.id', '=', 'products.category_id')
            ->select(
                'products.id',
                'products.sku',
                'products.name',
                'products.price',
                'products.image',
                'categories.name as category_name'
            );

        if ($request->get('category_id') !== null) {
            $query->where('category_id', $request->get('category_id'));
        }

        if ($request->get('brand_id') !== null) {
            $query->where('brand_id', $request->get('brand_id'));
        }

        if ($request->get('search') !== null && !empty($request->get('search'))) {
            $query->where('products.name', 'like', "%{$request->get('search')}%");
        }

        return $query
            ->where('status', true)
            ->orderBy('id', 'desc')->paginate(10);
    }
}
