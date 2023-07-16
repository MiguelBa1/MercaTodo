<?php

namespace App\Http\Controllers\Api\Admin\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Report\ReportRequest;
use App\Jobs\GenerateReport;
use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function generateReport(ReportRequest $request): JsonResponse
    {
        $startDate = Carbon::parse($request->validated()['start_date']);
        $endDate = Carbon::parse($request->validated()['end_date']);

        $report = Report::query()->create([
            'user_id' => $request->user()->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'report_type' => 'sales',
        ]);

        GenerateReport::dispatch($report);

        return response()->json([
            'message' => __('messages.being_generated', ['attribute' => 'Report']),
        ]);
    }
}
