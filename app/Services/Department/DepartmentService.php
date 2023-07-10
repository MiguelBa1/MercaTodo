<?php

namespace App\Services\Department;

use App\Models\Department;
use Illuminate\Support\Facades\Cache;

class DepartmentService
{
    public function getAllDepartments()
    {
        return Cache::remember('departments', 3600, fn () => Department::all());
    }
}
