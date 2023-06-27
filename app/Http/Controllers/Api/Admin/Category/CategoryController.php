<?php

namespace App\Http\Controllers\Api\Admin\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategoryController extends Controller
{
    /**
     * @return LengthAwarePaginator
     */
    public function index(): LengthAwarePaginator
    {
        return Category::query()->select(['id', 'name'])->paginate(10);
    }

    /**
     * @param CategoryRequest $request
     * @return void
     */
    public function store(CategoryRequest $request): void
    {
        $data = $request->validated();

        Category::query()->create($data);

        Cache::forget('categories');
    }

    /**
     * @param CategoryRequest $request
     * @param Category $category
     * @return void
     */
    public function update(CategoryRequest $request, Category $category): void
    {
        $data = $request->validated();

        $category->update($data);

        Cache::forget('categories');
    }
}
