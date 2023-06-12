<?php

namespace App\Http\Controllers\Api\Brands;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BrandRequest;
use App\Models\Brand;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

class AdminBrandController extends Controller
{
    /**
     * @return LengthAwarePaginator
     */
    public function index(): LengthAwarePaginator
    {
        return Brand::query()->select(['id', 'name'])->paginate(10);
    }

    /**
     * @param BrandRequest $request
     * @return JsonResponse
     */
    public function store(BrandRequest $request): JsonResponse
    {
        $data = $request->validated();

        $brand = Brand::query()->create($data);
        return response()->json(['message' => 'Brand created successfully', 'brand' => $brand]);
    }

    /**
     * @param BrandRequest $request
     * @param Brand $brand
     * @return JsonResponse
     */
    public function update(BrandRequest $request, Brand $brand): JsonResponse
    {
        $data = $request->validated();

        $brand->update($data);
        return response()->json(['message' => 'Brand updated successfully']);
    }
}
