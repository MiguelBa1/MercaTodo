<?php

namespace App\Jobs;

use App\Enums\ReportStatusEnum;
use App\Mail\ReportGenerated;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Report;
use App\Enums\OrderStatusEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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

        $report->data = [
            'total_sales' => $this->calculateTotalSales(),
            'total_orders' => $this->calculateTotalOrders(),
            'most_sold_product' => $this->calculateMostSoldProduct(),
            'products_sold_per_category' => $this->calculateProductsSoldPerCategory(),
            'sales_by_month' => $this->calculateSalesByMonth(),
            'top_selling_products' => $this->calculateTopSellingProducts(),
        ];

        $report->status = ReportStatusEnum::COMPLETED;
        $report->save();

        Mail::to($this->user->email)->send(new ReportGenerated($report));
    }

    protected function calculateTotalSales(): float
    {
        return Order::query()
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->where('status', OrderStatusEnum::COMPLETED)
            ->sum('total');
    }

    protected function calculateTotalOrders(): int
    {
        return Order::query()
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->where('status', OrderStatusEnum::COMPLETED)
            ->count();
    }

    protected function calculateMostSoldProduct(): object|null
    {
        return OrderDetail::query()
            ->select('product_name', DB::raw('SUM(quantity) as quantity'))
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->where('orders.status', OrderStatusEnum::COMPLETED)
            ->whereBetween('order_details.created_at', [$this->startDate, $this->endDate])
            ->groupBy('product_name')
            ->orderBy('quantity', 'desc')
            ->first();
    }

    protected function calculateProductsSoldPerCategory(): Collection|array
    {
        return OrderDetail::query()
            ->select('category_id', DB::raw('SUM(quantity) as quantity'))
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->where('orders.status', OrderStatusEnum::COMPLETED)
            ->whereBetween('order_details.created_at', [$this->startDate, $this->endDate])
            ->groupBy('category_id')
            ->get();
    }

    protected function calculateSalesByMonth(): Collection|array
    {
        return Order::query()
            ->select(DB::raw('SUM(total) as sales'), DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'))
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->where('status', OrderStatusEnum::COMPLETED)
            ->groupBy('month')
            ->get();
    }

    protected function calculateTopSellingProducts(): Collection|array
    {
        return OrderDetail::query()
            ->select('product_name', DB::raw('SUM(quantity) as total'))
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->where('orders.status', OrderStatusEnum::COMPLETED)
            ->whereBetween('order_details.created_at', [$this->startDate, $this->endDate])
            ->groupBy('product_name')
            ->orderBy('total', 'desc')
            ->take(10)
            ->get();
    }
}
