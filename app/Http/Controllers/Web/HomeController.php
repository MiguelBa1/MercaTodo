<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\ViewModels\ProductsViewModel;

class HomeController extends Controller
{
    public function index(Request $request): Response
    {
        $category_id = $request->input('category_id');
        $brand_id = $request->input('brand_id');
        $searchQuery = $request->input('search');

        $viewModel = new ProductsViewModel($category_id, $brand_id, $searchQuery);

        return Inertia::render('Home', $viewModel);
    }
}
