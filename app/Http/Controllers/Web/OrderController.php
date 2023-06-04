<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Inertia\Response;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Orders/Index');
    }
}
