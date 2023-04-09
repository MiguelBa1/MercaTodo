<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function list(): JsonResponse
    {
        $roles = Role::all()->pluck('name');

        return response()->json($roles);
    }
}
