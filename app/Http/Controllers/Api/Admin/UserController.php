<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function list(): LengthAwarePaginator
    {
        // Get all users with their roles
        return User::query()->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                DB::raw('IF(users.status = 1, "Active", "Inactive") as status'),
                'roles.name as role_name'
            )->orderBy('users.id', 'asc')
            ->paginate(10);
    }

    public function update(User $user): JsonResponse
    {
        $user->setAttribute('status', !$user->getAttribute('status'));
        $user->save();

        return response()->json(['message' => 'User status updated successfully']);
    }
}
