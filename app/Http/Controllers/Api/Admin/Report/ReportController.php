<?php

namespace App\Http\Controllers\Api\Admin\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Report\ReportRequest;
use App\Jobs\GenerateReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function generateReport(ReportRequest $request): JsonResponse
    {
        $startDate = Carbon::parse($request->validated()['start_date']);
        $endDate = Carbon::parse($request->validated()['end_date']);

        GenerateReport::dispatch(Auth::user(), $startDate, $endDate);

        return response()->json([
            'message' => 'Report is being generated',
        ]);
    }
}
