<?php

namespace App\Http\Controllers\Admin;

//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    // This function returns an Inertia response
    // that will render the Admin/Users.vue file.

    // This function returns a view contain
    // The view is rendered using Inertia.js.
    public function index(): Response
    {
        // TODO: Add this view
        return Inertia::render('Admin/Index');
    }

}
