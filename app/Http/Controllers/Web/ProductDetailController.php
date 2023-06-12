<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class ProductDetailController extends Controller
{
    public function show(Product $product): Response
    {
        $relatedProducts = Product::query()
            ->where('category_id', $product->getAttribute('category_id'))
            ->where('id', '!=', $product->getAttribute('id'))
            ->where('status', '=', true)
            ->select('id', 'name', 'price', 'image')
            ->inRandomOrder()
            ->limit(4)->get();

        return Inertia::render('Products/Show', [
            'product' => $product->load('category:id,name', 'brand:id,name')
                ->only('id', 'name', 'price', 'stock', 'description', 'image', 'category', 'brand'),
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
