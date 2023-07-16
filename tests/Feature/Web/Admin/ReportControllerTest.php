<?php

namespace Tests\Feature\Web\Admin;

use App\Enums\ReportStatusEnum;
use App\Models\Report;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Inertia\Testing\AssertableInertia;
use Tests\Feature\Utilities\UserTestCase;

class ReportControllerTest extends UserTestCase
{
    use RefreshDatabase;

    protected Report $report;

    public function setUp(): void
    {
        parent::setUp();

        /** @var Report $report */
        $report = Report::query()->create([
            'user_id' => $this->adminUser->id,
            'status' => ReportStatusEnum::COMPLETED->value,
            'report_type' => 'sales',
            'start_date' => Carbon::now()->subYear(),
            'end_date' => Carbon::now(),
        ]);

        $this->report = $report;
    }

    public function testIndexRendersCorrectView(): void
    {
        $response = $this
            ->actingAs($this->adminUser)
            ->get(route('admin.view.reports'));

        /** @var Report $lastReport */
        $lastReport = Report::all()->last();

        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page) => $page
                ->component('Admin/Report/Index')
                ->has(
                    'lastReport',
                    fn (AssertableInertia $page) => $page
                        ->where('id', $lastReport->id)
                        ->etc()
                )
        );
    }

    public function testShowRendersCorrectView(): void
    {
        $response = $this
            ->actingAs($this->adminUser)
            ->get(route('admin.view.report', $this->report->id));

        $response->assertOk();
        $response->assertInertia(
            fn (AssertableInertia $page) => $page
                ->component('Admin/Report/Show')->has(
                    'report',
                    fn (AssertableInertia $page) => $page
                        ->where('id', $this->report->id)
                        ->where('start_date', $this->report->start_date->format('Y-m-d H:i:s'))
                        ->where('end_date', $this->report->end_date->format('Y-m-d H:i:s'))
                        ->etc()
                )
        );
    }

    public function testShowReturns404WhenReportIsPending(): void
    {
        $report = Report::query()->create([
            'user_id' => $this->adminUser->id,
            'status' => ReportStatusEnum::PENDING->value,
            'report_type' => 'sales',
            'start_date' => Carbon::now()->subYear(),
            'end_date' => Carbon::now(),
        ]);

        $response = $this
            ->actingAs($this->adminUser)
            ->get(route('admin.view.report', $report->id));

        $response->assertNotFound();
    }
}
