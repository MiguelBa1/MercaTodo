<?php

namespace App\Http\Controllers\Api\Admin\Brand;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BrandRequest;
use App\Models\Brand;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BrandController extends Controller
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
     * @return void
     */
    public function store(BrandRequest $request): void
    {
        $data = $request->validated();

        Brand::query()->create($data);

        Cache::forget('brands');
    }

    /**
     * @param BrandRequest $request
     * @param Brand $brand
     * @return void
     */
    public function update(BrandRequest $request, Brand $brand): void
    {
        $data = $request->validated();

        $brand->update($data);

        Cache::forget('brands');
    }
}
