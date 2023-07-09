<?php

namespace App\Http\Controllers\Web\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Services\Brand\BrandService;
use App\Services\Category\CategoryService;
use App\Services\Product\ProductService;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(): Response
    {
        $products = (new ProductService())->getAllProducts();

        return Inertia::render('Admin/Products/Index', [
            'products' => $products
        ]);
    }

    public function edit(Product $product): Response
    {
        return Inertia::render('Admin/Products/Edit', [
            'product' => fn () => $product->load(['category:id,name', 'brand:id,name']),
            'categories' => fn () => (new CategoryService())->getAllCategories(),
            'brands' => fn () => (new BrandService())->getAllBrands(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Products/Create', [
            'categories' => fn () => (new CategoryService())->getAllCategories(),
            'brands' => fn () => (new BrandService())->getAllBrands(),
        ]);
    }
}
