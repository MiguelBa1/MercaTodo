<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

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

        return Inertia::render('Admin/Users/Edit', [
            'user' => $user->withoutRelations()
        ]);
    }

}
