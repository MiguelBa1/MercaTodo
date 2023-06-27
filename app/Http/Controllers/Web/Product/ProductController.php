<?php

namespace App\Http\Controllers\Web\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function show(Product $product): Response
    {
        $relatedProducts = Product::query()
            ->with('category:id,name')
            ->where('category_id', $product->category_id)
            ->where('status', true)
            ->whereKeyNot($product->getKey())
            ->select(['id', 'name', 'price', 'image', 'category_id'])
            ->inRandomOrder()
            ->limit(6)
            ->get();

        return Inertia::render('Products/Show', [
            'product' => $product->load('category:id,name', 'brand:id,name')
                ->only('id', 'name', 'price', 'stock', 'description', 'image', 'category', 'brand'),
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
