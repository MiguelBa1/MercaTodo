<?php

namespace App\Http\Controllers\Api\City;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\City;

class CityController extends Controller
{
    public function index(int $department_id): JsonResponse
    {
        /** @var City $cities */
        $cities = City::query()->select('id', 'name')->where('department_id', $department_id)->get();

        return response()->json($cities);
    }
}
