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
        // Render the Admin/Index.vue file
        return Inertia::render('Admin/Users/Index');
    }

    public function edit(User $user): Response
    {
        // Check if the user is the current user
        if ($user->getAttribute('id') === auth()->user()['id']) {
            return Inertia::render('Profile/Edit');
        }
        // User with his first role
        $user->setAttribute('role_name', $user->getAttribute('roles')->first()->getAttribute('name'));

        return Inertia::render('Admin/Users/Edit', [
            // Pass the user without roles to the view
            'user' => $user->withoutRelations()
        ]);
    }

}
