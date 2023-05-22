<?php

namespace App\Http\Controllers\Api\Brands;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\JsonResponse;

class BrandController extends Controller
{
    public function index(): JsonResponse
    {
        $brands = Brand::query()->select('id', 'name')->get();

        return response()->json([
            'brands' => $brands,
        ]);
    }
}
