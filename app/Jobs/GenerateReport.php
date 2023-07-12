<?php

namespace App\Jobs;

use App\Enums\ReportStatusEnum;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class GenerateReport implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        protected $user,
        protected Carbon $startDate,
        protected Carbon $endDate,
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        /** @var Report $report */
        $report = Report::query()->create([
            'report_type' => 'sales',
            'user_id' => $this->user->id,
        ]);

        $totalSales = $this->calculateTotalSales();
        $totalOrders = $this->calculateTotalOrders();
        $mostSoldProduct = $this->calculateMostSoldProduct();
        $productsSoldPerCategory = $this->calculateProductsSoldPerCategory();
        $salesByMonth = $this->calculateSalesByMonth();
        $topSellingProducts = $this->calculateTopSellingProducts();

        $report->data = [
            'total_sales' => $totalSales,
            'total_orders' => $totalOrders,
            'most_sold_product' => $mostSoldProduct,
            'products_sold_per_category' => $productsSoldPerCategory,
            'sales_by_month' => $salesByMonth,
            'top_selling_products' => $topSellingProducts,
        ];

        $report->status = ReportStatusEnum::COMPLETED;
        $report->save();

    }

    protected function calculateTotalSales(): float
    {
        return Order::query()->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->sum('total');
    }

    protected function calculateTotalOrders(): int
    {
        return Order::query()->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->count();
    }

    protected function calculateMostSoldProduct(): object|null
    {
        return OrderDetail::query()->select('product_name', DB::raw('SUM(quantity) as quantity'))
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->groupBy('product_name')
            ->orderBy('quantity', 'desc')
            ->first();
    }

    protected function calculateProductsSoldPerCategory(): Collection|array
    {
        return OrderDetail::query()->select('category_id', DB::raw('SUM(quantity) as quantity'))
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->whereBetween('order_details.created_at', [$this->startDate, $this->endDate])
            ->groupBy('category_id')
            ->get();
    }
    protected function calculateSalesByMonth(): Collection|array
    {
        return Order::query()->select(DB::raw('SUM(total) as sales'), DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'))
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->groupBy('month')
            ->get();
    }

    protected function calculateTopSellingProducts(): Collection|array
    {
        return OrderDetail::query()->select('product_name', DB::raw('SUM(quantity) as total'))
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->groupBy('product_name')
            ->orderBy('total', 'desc')
            ->take(10)
            ->get();
    }
}
