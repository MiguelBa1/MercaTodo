<?php

namespace App\Http\Controllers\Web\Admin\User;

use App\Enums\DocumentTypeEnum;
use App\Enums\RoleEnum;
use App\Enums\PermissionEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Department\DepartmentService;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

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

        return Inertia::render('Admin/Users/Edit', [
            'user' => $user->load(['roles:name', 'permissions:name', 'city:id,name,department_id']),
            'departments' => (new DepartmentService())->getAllDepartments(),
            'document_types' => array_column(DocumentTypeEnum::cases(), 'value'),
            'roles' => RoleEnum::cases(),
            'permissions' => PermissionEnum::cases(),
        ]);
    }
}
