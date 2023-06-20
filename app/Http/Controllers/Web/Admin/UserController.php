<?php

namespace App\Http\Controllers\Web\Admin;

use App\Enums\DocumentTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request): Response
    {
        $currentUserId = $request->user()->id;

        $users = User::with([
            'roles:id,name',
            'city:id,name'
        ])
            ->select(
                'id',
                'name',
                'surname',
                'email',
                'document',
                'document_type',
                'phone',
                'address',
                'status',
                'city_id'
            )
            ->whereNot('id', $currentUserId)
            ->latest('id')
            ->paginate(10);

        return Inertia::render('Admin/Users/Index', [
            'users' => $users
        ]);
    }

    public function edit(Request $request, User $user): Response
    {
        if ($user->id === $request->user()->id) {
            return Inertia::render('Profile/Edit');
        }

        $userData = $user->withoutRelations()->toArray();
        $userData['role_name'] = $user->roles->first()->name;
        $userData['city_name'] = $user->city->name;
        $userData['department_id'] = $user->city->department_id;

        return Inertia::render('Admin/Users/Edit', [
            'user' => $userData,
            'departments' => Department::all('id', 'name'),
            'document_types' => array_column(DocumentTypeEnum::cases(), 'value'),
            'roles' => Role::all('name')
        ]);
    }
}
