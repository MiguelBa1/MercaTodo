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
        // Render the Admin/Users.vue file
        return Inertia::render('Admin/Users');
    }

    public function list(): LengthAwarePaginator
    {
        // Users with roles paginated 10 per page
        return User::with('roles')->paginate(10);
    }
    public function manageStatus(User $user): JsonResponse
    {
        // Disable the user
        $user->setAttribute('status', !$user->getAttribute('status'));
        $user->save();

        return response()->json(['message' => 'User status updated successfully']);
    }
}
