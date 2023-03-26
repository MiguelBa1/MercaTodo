<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{

    public function index(): Response
    {
        // Render the Admin/Index.vue file
        return Inertia::render('Admin/Users/Index');
    }

    public function list(): LengthAwarePaginator
    {
        // Get all users with their roles
        return User::query()->join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->select('users.*', 'roles.name as role_name')->orderBy('users.id', 'asc')
            ->paginate(10);
    }
    public function manageStatus(User $user): JsonResponse
    {
        // Disable the user
        $user->setAttribute('status', !$user->getAttribute('status'));
        $user->save();

        return response()->json(['message' => 'User status updated successfully']);
    }

    public function edit(User $user): Response
    {
        // Render the Admin/EditUser.vue file
        return Inertia::render('Admin/Users/EditUser', [
            'user' => $user
        ]);
    }
}
