<?php

namespace App\Http\Controllers\Web\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\HomeRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
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
            ->orderBy('id', 'desc')
            ->paginate(10);

        return Inertia::render('Home', [
            'products' => fn () => $products,
            'brands' => fn () => Brand::query()->select('id', 'name')->get(),
            'categories' => fn () => Category::query()->select('id', 'name')->get(),
            'filters' => fn () => $request->all(),
        ]);
    }
}
