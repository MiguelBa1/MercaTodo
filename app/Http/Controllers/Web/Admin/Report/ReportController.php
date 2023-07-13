<?php

namespace App\Http\Controllers\Web\Admin\Report;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Report;

class ReportController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Report/Index');
    }

    public function show(Report $report): Response
    {
        return Inertia::render('Admin/Report/Show', [
            'report' => $report,
        ]);
    }
}
