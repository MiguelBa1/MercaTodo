<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserController extends Controller
{
    public function index(): LengthAwarePaginator
    {
        $currentUserId = auth()->user()['id'];

        // Get all users with their roles
        return User::query()->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.document',
                'users.document_type',
                'users.phone',
                'users.address',
                'users.city_id as city_name',
                'status',
                'roles.name as role_name'
            )->where('users.id', '!=', $currentUserId)
            ->latest('users.id')
            ->paginate(10);
    }

    public function update(User $user): JsonResponse
    {
        $user->setAttribute('status', !$user->getRawOriginal('status'));
        $user->save();

        return response()->json(['message' => 'User status updated successfully']);
    }
}
