<?php

namespace App\Http\Controllers\Web\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(): Response
    {
        $products = Product::query()
            ->with(['category:id,name', 'brand:id,name'])
            ->select(
                'id',
                'sku',
                'name',
                'price',
                'stock',
                'status',
                'category_id',
                'brand_id',
            )
            ->latest()
            ->paginate(10);

        return Inertia::render('Admin/Products/Index', [
            'products' => $products
        ]);
    }

    public function edit(Product $product): Response
    {
        return Inertia::render('Admin/Products/Edit', [
            'product' => $product->load(['category:id,name', 'brand:id,name'])
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Products/Create');
    }
}
