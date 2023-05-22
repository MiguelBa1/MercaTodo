<?php

namespace App\Http\Controllers\Web\Admin;

use App\Enums\DocumentTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Department;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Users/Index');
    }

    public function edit(User $user): Response
    {
        if ($user->getAttribute('id') === auth()->user()['id']) {
            return Inertia::render('Profile/Edit');
        }
        $user->setAttribute('role_name', $user->getAttribute('roles')->first()->getAttribute('name'));

        $userData = $user->withoutRelations()->toArray();
        $userData['city_name'] = $user->getCityNameAttribute($userData['city_id']);
        $userData['department_id'] = City::query()->where('id', $userData['city_id'])->first()->getAttribute('department_id');

        return Inertia::render('Admin/Users/Edit', [
            'user' => $userData,
            'departments' => Department::all('id', 'name'),
            'document_types' => DocumentTypeEnum::getValues(),
            'roles' => Role::all('name')
        ]);
    }
}
