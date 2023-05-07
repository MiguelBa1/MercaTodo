<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use App\ViewModels\ProductsViewModel;

class HomeController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Home');
    }
}
