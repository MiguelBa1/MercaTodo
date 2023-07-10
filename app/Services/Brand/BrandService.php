<?php

namespace App\Services\Brand;

use App\Models\Brand;
use Illuminate\Support\Facades\Cache;

class BrandService
{
    public function getAllBrands()
    {
        return Cache::remember('brands', 3600, function () {
            return Brand::all('id', 'name');
        });
    }
}
