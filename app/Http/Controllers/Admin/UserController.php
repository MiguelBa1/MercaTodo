<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

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
            ->select('users.id', 'users.name', 'users.email', DB::raw('IF(users.status = 1, "Active", "Inactive") as status'),
                'roles.name as role_name')->orderBy('users.id', 'asc')
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
        if ($user->getAttribute('id') === auth()->user()->getAttribute('id')) {
            // Render the Profile/Edit.vue file
            return Inertia::render('Profile/Edit');
        }
        // User with his first role
        $user->setAttribute('role_name', $user->getAttribute('roles')->first()->getAttribute('name'));

        // Render the Admin/Edit.vue file
        return Inertia::render('Admin/Users/Edit', [
            // Pass the user without roles to the view
            'user' => $user->withoutRelations()
        ]);
    }

    // Update password of a user using the Admin panel

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ValidationException
     */
    public function updatePassword(User $user): JsonResponse
    {
        // Validate the request
        $this->validate(request(), [
            'password' => ['required', Password::defaults(), 'confirmed']
        ]);

        // Update the password
        $user->setAttribute('password', bcrypt(request()->get('password')));
        $user->save();

        if ($user->getAttribute('id') === auth()->user()->getAttribute('id')) {
            // If the user is the current user, log him out
            auth()->logout();
        }

        return response()->json(['message' => 'Password updated successfully']);
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws ValidationException
     */
    public function updateProfile(User $user): JsonResponse
    {

        // Validate the request
        $this->validate(request(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->getAttribute('id')],
            'role_name' => ['required', 'string']
        ]);

        // Update the profile
        $user->setAttribute('name', request()->get('name'));
        $user->setAttribute('email', request()->get('email'));
        $user->syncRoles(request()->get('role_name'));
        $user->save();

        return response()->json(['message' => 'Profile updated successfully']);
    }
}
