<?php

namespace Tests\Feature\Report;

use App\Enums\OrderStatusEnum;
use App\Enums\ReportStatusEnum;
use App\Jobs\GenerateReport;
use App\Mail\ReportFailed;
use App\Mail\ReportGenerated;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Report;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Tests\Feature\Utilities\UserTestCase;

class GenerateReportTest extends UserTestCase
{
    use RefreshDatabase;

    protected Report $report;

    public function setUp(): void
    {
        parent::setUp();

        /** @var Report $report */
        $report = Report::query()->create([
            'user_id' => $this->adminUser->id,
            'status' => ReportStatusEnum::PENDING,
            'report_type' => 'sales',
            'start_date' => Carbon::now()->subYear(),
            'end_date' => Carbon::now(),
        ]);

        $this->report = $report;
    }

    public function testReportIsGenerated(): void
    {
        GenerateReport::dispatch($this->report);

        $this->assertDatabaseHas('reports', [
            'user_id' => $this->adminUser->id,
            'status' => ReportStatusEnum::COMPLETED->value,
            'start_date' => $this->report->start_date,
            'end_date' => $this->report->end_date,
        ]);
    }

    public function testEmailIsSentWhenReportIsGenerated(): void
    {
        Mail::fake();

        GenerateReport::dispatch($this->report);

        Mail::assertQueued(ReportGenerated::class);
    }

    public function testReportIsGeneratedWithCorrectData(): void
    {
        GenerateReport::dispatch($this->report);

        $this->assertDatabaseHas('reports', [
            'user_id' => $this->adminUser->id,
            'status' => ReportStatusEnum::COMPLETED->value,
            'start_date' => $this->report->start_date,
            'end_date' => $this->report->end_date,
        ]);

        /** @var Report $report */
        $report = Report::query()->first();

        $this->assertNotNull($report->data);

        $this->assertArrayHasKey('total_sales', $report->data);
        $this->assertArrayHasKey('total_orders', $report->data);
        $this->assertArrayHasKey('most_sold_product', $report->data);
        $this->assertArrayHasKey('products_sold_per_category', $report->data);
        $this->assertArrayHasKey('sales_by_month', $report->data);
        $this->assertArrayHasKey('top_selling_products', $report->data);
    }

    public function testCalculateTotalSales(): void
    {
        Product::factory()->count(5)->create(
            [
                'brand_id' => Brand::factory()->create()->id,
                'category_id' => Category::factory()->create()->id,
                'price' => 10,
            ]
        );

        /** @var Order $order1 */
        $order1 = Order::factory()
            ->create([
                'status' => OrderStatusEnum::COMPLETED->value,
                'user_id' => $this->adminUser->id,
                'total' => 100
            ]);

        /** @var Order $order2 */
        $order2 = Order::factory()
            ->create([
                'status' => OrderStatusEnum::COMPLETED->value,
                'user_id' => $this->adminUser->id,
                'total' => 200
            ]);

        GenerateReport::dispatch($this->report);

        $this->assertDatabaseHas('reports', [
            'user_id' => $this->adminUser->id,
            'status' => ReportStatusEnum::COMPLETED->value,
        ]);

        $reportData = Report::query()->first()->data;

        $totalSales = $order1->total + $order2->total;

        $this->assertEquals($totalSales, $reportData['total_sales']);
    }

    public function testCalculateTotalOrders(): void
    {
        Product::factory()->count(5)->create(
            [
                'brand_id' => Brand::factory()->create()->id,
                'category_id' => Category::factory()->create()->id,
                'price' => 10,
            ]
        );

        Order::factory()
            ->count(5)
            ->create([
                'status' => OrderStatusEnum::COMPLETED->value,
                'user_id' => $this->adminUser->id,
                'total' => 100
            ]);

        GenerateReport::dispatch($this->report);

        $this->assertDatabaseHas('reports', [
            'user_id' => $this->adminUser->id,
            'status' => ReportStatusEnum::COMPLETED->value,
        ]);

        $reportData = Report::query()->first()->data;

        $this->assertEquals(5, $reportData['total_orders']);
    }

    public function testCalculateMostSoldProduct(): void
    {
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'brand_id' => Brand::factory()->create()->id,
            'category_id' => Category::factory()->create()->id,
            'price' => 10,
        ]);

        OrderDetail::factory()->count(5)->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => 2,
            'order_id' => Order::factory()->create([
                'status' => OrderStatusEnum::COMPLETED->value,
                'user_id' => $this->adminUser->id,
                'total' => 100
            ])->id,
        ]);

        GenerateReport::dispatch($this->report);

        $reportData = Report::query()->first()->data;

        $this->assertEquals($product->name, $reportData['most_sold_product']['product_name']);
    }

    public function testJobFailure(): void
    {
        Mail::fake();

        $job = new GenerateReport($this->report);

        $job->failed(new Exception('Something went wrong!'));

        // Verify that the report status is failed
        $this->assertDatabaseHas('reports', [
            'id' => $this->report->id,
            'status' => ReportStatusEnum::FAILED->value,
        ]);

        // Verify that the admin user received an email with the error
        Mail::assertSent(ReportFailed::class, function ($mail) {
            return $mail->hasTo($this->adminUser->email);
        });
    }
}
