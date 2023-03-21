<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    // This function returns an Inertia response
    // that will render the Admin/Users.vue file.
    public function index(): Response
    {
        return Inertia::render('Admin/Index');
    }

    // This function returns a view contain
    // The view is rendered using Inertia.js.
    public function manageUsers(): Response
    {
        $users = User::with(['role' => function ($query) {
            $query->select('id', 'name', 'description');
        }])->get();
        return Inertia::render('Admin/Users', ['users' => $users]);
    }

}
