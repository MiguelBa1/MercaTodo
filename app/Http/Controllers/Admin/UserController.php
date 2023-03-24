<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{

    public function index(): Response
    {
        // Users with roles paginated 10 per page
        $users = User::with('roles')->paginate(10);

        // Render the Admin/Users.vue file
        return Inertia::render('Admin/Users', ['users' => $users]);
    }
}
