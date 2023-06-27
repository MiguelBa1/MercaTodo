<?php

namespace App\Http\Controllers\Web\Admin\User;

use App\Enums\DocumentTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use App\Services\User\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Cache;
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
            'departments' => Cache::remember('departments', 3600, fn () => Department::all()),
            'document_types' => array_column(DocumentTypeEnum::cases(), 'value'),
            'roles' => Cache::remember('roles', Carbon::now()->addWeek(), fn () => Role::all()),
        ]);
    }
}
