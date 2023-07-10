<?php

namespace App\Services\Category;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class CategoryService
{
    public function getAllCategories()
    {
        return Cache::remember('categories', 3600, function () {
            return Category::all('id', 'name');
        });
    }
}
