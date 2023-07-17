<?php

namespace App\Http\Controllers\Web\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\Product\ProductService;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function show(Product $product): Response
    {
        $productService = new ProductService();

        $relatedProducts = $productService->getRelatedProducts($product);

        return Inertia::render('Products/Show', [
            'product' => $product->load('category:id,name', 'brand:id,name')
                ->only('id', 'name', 'price', 'stock', 'description', 'image', 'category', 'brand'),
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
