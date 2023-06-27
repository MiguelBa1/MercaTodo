<?php

namespace App\Http\Controllers\Web\Admin\Auxiliary;

use App\Http\Controllers\Controller;
use Inertia\Response;

class AuxiliaryTablesController extends Controller
{
    public function index(): Response
    {
        return inertia('Admin/AuxiliaryTables/Index');
    }
}
