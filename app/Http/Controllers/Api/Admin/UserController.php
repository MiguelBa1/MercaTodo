<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
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
        // Disable the user
        $user->setAttribute('status', !$user->getAttribute('status'));
        $user->save();

        // Make a log
        Log::warning('[STATUS]', [
            'admin_id' => auth()->user()->getAttribute('id'),
            'user_id' => $user->getAttribute('id'),
            'user_name' => $user->getAttribute('name'),
            'user_email' => $user->getAttribute('email'),
            'status' => $user->getAttribute('status') ? 'Active' : 'Inactive'
        ]);

        return response()->json(['message' => 'User status updated successfully']);
    }
}
