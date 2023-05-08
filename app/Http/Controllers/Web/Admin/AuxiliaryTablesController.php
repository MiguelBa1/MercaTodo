<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Response;

class AuxiliaryTablesController extends Controller
{
    public function index(): Response
    {
        return inertia('Admin/AuxiliaryTables/Index');
    }
}
