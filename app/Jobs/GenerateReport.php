<?php

namespace App\Jobs;

use App\Enums\OrderStatusEnum;
use App\Enums\ReportStatusEnum;
use App\Mail\ReportFailed;
use App\Mail\ReportGenerated;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Report;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class GenerateReport implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        protected Report $report,
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->report->data = [
            'total_sales' => $this->calculateTotalSales(),
            'total_orders' => $this->calculateTotalOrders(),
            'most_sold_product' => $this->calculateMostSoldProduct(),
            'products_sold_per_category' => $this->calculateProductsSoldPerCategory(),
            'sales_by_month' => $this->calculateSalesByMonth(),
            'top_selling_products' => $this->calculateTopSellingProducts(),
        ];

        $this->report->status = ReportStatusEnum::COMPLETED;
        $this->report->save();

        Mail::to($this->report->user->email)->queue(new ReportGenerated($this->report));
    }

    public function failed(Exception $exception): void
    {
        $this->report->status = ReportStatusEnum::FAILED;
        $this->report->save();

        Log::error('Report generation failed', [
            'user_id' => $this->report->user->id,
            'exception' => $exception->getMessage(),
        ]);

        Mail::to($this->report->user->email)->send(new ReportFailed($this->report->user));
    }

    protected function calculateTotalSales(): float
    {
        return Order::query()
            ->whereBetween('created_at', [$this->report->start_date, $this->report->end_date])
            ->where('status', OrderStatusEnum::COMPLETED)
            ->sum('total');
    }

    protected function calculateTotalOrders(): int
    {
        return Order::query()
            ->whereBetween('created_at', [$this->report->start_date, $this->report->end_date])
            ->where('status', OrderStatusEnum::COMPLETED)
            ->count();
    }

    protected function calculateMostSoldProduct(): object|array
    {
        $result = OrderDetail::query()
            ->select('product_name', DB::raw('SUM(quantity) as quantity'))
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->where('orders.status', OrderStatusEnum::COMPLETED)
            ->whereBetween('order_details.created_at', [$this->report->start_date, $this->report->end_date])
            ->groupBy('product_name')
            ->orderBy('quantity', 'desc')
            ->first();
        return $result ?: (object) [];
    }

    protected function calculateProductsSoldPerCategory(): Collection|array
    {
        return OrderDetail::query()
            ->select('categories.name as category_name', DB::raw('SUM(quantity) as quantity'))
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->where('orders.status', OrderStatusEnum::COMPLETED)
            ->whereBetween('order_details.created_at', [$this->report->start_date, $this->report->end_date])
            ->groupBy('category_name')
            ->get();
    }

    protected function calculateSalesByMonth(): Collection|array
    {
        return Order::query()
            ->select(DB::raw('SUM(total) as sales'), DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'))
            ->whereBetween('created_at', [$this->report->start_date, $this->report->end_date])
            ->where('status', OrderStatusEnum::COMPLETED)
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();
    }

    protected function calculateTopSellingProducts(): Collection|array
    {
        return OrderDetail::query()
            ->select('product_name', DB::raw('SUM(quantity) as total'))
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->where('orders.status', OrderStatusEnum::COMPLETED)
            ->whereBetween('order_details.created_at', [$this->report->start_date, $this->report->end_date])
            ->groupBy('product_name')
            ->orderBy('total', 'desc')
            ->take(10)
            ->get();
    }
}
