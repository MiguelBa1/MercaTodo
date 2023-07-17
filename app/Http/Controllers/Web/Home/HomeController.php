<?php

namespace App\Http\Controllers\Web\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\HomeRequest;
use App\Services\Brand\BrandService;
use App\Services\Category\CategoryService;
use App\Services\Product\ProductService;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(HomeRequest $request): Response
    {
        $filters = $request->only('category_id', 'brand_id', 'search');
        $products = (new ProductService())->getFilteredProducts($filters);

        return Inertia::render('Home/Index', [
            'products' => fn () => $products,
            'brands' => fn () => (new BrandService())->getAllBrands(),
            'categories' => fn () => (new CategoryService())->getAllCategories(),
            'filters' => fn () => $request->all(),
        ]);
    }
}
