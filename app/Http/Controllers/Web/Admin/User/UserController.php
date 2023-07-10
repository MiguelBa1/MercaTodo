<?php

namespace App\Http\Controllers\Web\Admin\User;

use App\Enums\DocumentTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Department\DepartmentService;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request): Response
    {
        $users = (new UserService())->getAllUsersExceptCurrent($request->user()->id);

        return Inertia::render('Admin/Users/Index', [
            'users' => $users
        ]);
    }

    public function edit(Request $request, User $user): Response
    {
        if ($user->id === $request->user()->id) {
            return Inertia::render('Profile/Edit');
        }

        $userData = (new UserService())->getUserDataForEdit($user);

        return Inertia::render('Admin/Users/Edit', [
            'user' => $userData,
            'departments' => (new DepartmentService())->getAllDepartments(),
            'document_types' => array_column(DocumentTypeEnum::cases(), 'value'),
            'roles' => Role::all('id', 'name'),
        ]);
    }
}
