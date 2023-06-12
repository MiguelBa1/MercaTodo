<?php

namespace App\Http\Controllers\Api\Categories;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

class AdminCategoryController extends Controller
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
     * @return JsonResponse
     */
    public function store(CategoryRequest $request): JsonResponse
    {
        $data = $request->validated();

        $category = Category::query()->create($data);
        return response()->json(['message' => 'Category created successfully', 'category' => $category]);
    }

    /**
     * @param CategoryRequest $request
     * @param Category $category
     * @return JsonResponse
     */
    public function update(CategoryRequest $request, Category $category): JsonResponse
    {
        $data = $request->validated();

        $category->update($data);
        return response()->json(['message' => 'Category updated successfully']);
    }
}
