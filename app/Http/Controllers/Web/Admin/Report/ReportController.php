<?php

namespace App\Http\Controllers\Web\Admin\Report;

use App\Enums\ReportStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReportController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Admin/Report/Index', [
            'lastReport' => Report::query()
                ->select('id', 'status')
                ->where('status', ReportStatusEnum::COMPLETED->value)
                ->where('user_id', $request->user()->id)
                ->latest('id')
                ->first(),
        ]);
    }

    public function show(Report $report): Response
    {
        return Inertia::render('Admin/Report/Show', [
            'report' => $report->only('id', 'data', 'start_date', 'end_date'),
        ]);
    }
}
