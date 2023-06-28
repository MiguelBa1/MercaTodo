<?php

namespace App\Http\Controllers\Web\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\HomeRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(HomeRequest $request): Response
    {
        $products = Product::query()->with('category:id,name')
            ->select(['id', 'sku', 'name', 'price', 'image', 'category_id'])
            ->when($request->filled('category_id'), function ($query) use ($request) {
                return $query->where('category_id', $request->get('category_id'));
            })
            ->when($request->filled('brand_id'), function ($query) use ($request) {
                return $query->where('brand_id', $request->get('brand_id'));
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                return $query->where('name', 'like', "%{$request->get('search')}%");
            })
            ->where('status', true)
            ->latest()
            ->paginate(10);

        return Inertia::render('Home/Index', [
            'products' => fn () => $products,
            'brands' => fn () => Cache::remember('brands', 3600, fn () => Brand::all('id', 'name')),
            'categories' => fn () => Cache::remember('categories', 3600, fn () => Category::all('id', 'name')),
            'filters' => fn () => $request->all(),
        ]);
    }
}
