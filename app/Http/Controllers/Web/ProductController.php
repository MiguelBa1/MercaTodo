<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function show(Product $product): Response
    {
        $relatedProducts = Product::query()
            ->where('category_id', $product->getAttribute('category_id'))
            ->where('id', '!=', $product->getAttribute('id'))
            ->where('status', true)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return Inertia::render('Products/Show', [
            'product' => $product->load('category:id,name', 'brand:id,name')
                ->only('name', 'price', 'stock', 'description', 'image', 'category', 'brand'),
            'relatedProducts' => $relatedProducts->map(function ($product) {
                return $product->only('id', 'name', 'price', 'image');
            }),
        ]);
    }
}
