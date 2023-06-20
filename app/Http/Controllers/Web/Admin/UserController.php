<?php

namespace App\Http\Controllers\Web\Admin;

use App\Enums\DocumentTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request, UserService $userService): Response
    {
        $users = $userService->getAllUsersExceptCurrent($request->user()->id);

        return Inertia::render('Admin/Users/Index', [
            'users' => $users
        ]);
    }

    public function edit(Request $request, User $user, UserService $userService): Response
    {
        if ($user->id === $request->user()->id) {
            return Inertia::render('Profile/Edit');
        }

        $userData = $userService->getUserDataForEdit($user);

        return Inertia::render('Admin/Users/Edit', [
            'user' => $userData,
            'departments' => Department::all('id', 'name'),
            'document_types' => array_column(DocumentTypeEnum::cases(), 'value'),
            'roles' => Role::all('name')
        ]);
    }
}
