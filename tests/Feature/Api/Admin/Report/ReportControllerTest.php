<?php

namespace Tests\Feature\Api\Admin\Report;

use App\Jobs\GenerateReport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\Feature\Utilities\UserTestCase;

class ReportControllerTest extends UserTestCase
{
    use RefreshDatabase;

    public function testOnlyAdminCanGenerateReport(): void
    {
        $this->actingAs($this->customerUser)
            ->postJson(route('api.admin.report.generate'), [
                'start_date' => '2021-01-01',
                'end_date' => '2021-01-31',
            ])
            ->assertForbidden();
    }

    public function testGenerateReportDispatchesJob(): void
    {
        Queue::fake();

        $response = $this->actingAs($this->adminUser)
            ->postJson(route('api.admin.report.generate'), [
                'start_date' => '2021-01-01',
                'end_date' => '2021-01-31',
            ])
            ->assertSuccessful();

        $response->assertJson([
            'message' => __('messages.being_generated', ['attribute' => 'Report']),
        ]);

        Queue::assertPushed(GenerateReport::class);
    }
}
