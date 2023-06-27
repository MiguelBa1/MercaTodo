<?php

namespace App\Http\Controllers\Web\Cart;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Cart/Index');
    }
}
