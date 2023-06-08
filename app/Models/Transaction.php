<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\TransactionStatusEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Transaction
 *
 * @property int $id
 * @property int $order_id
 * @property TransactionStatusEnum $status
 * @property string $process_url
 * @property int $request_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Order $order
 */
class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'process_url',
        'request_id',
        'status',
    ];

    protected $casts = [
        'status' => TransactionStatusEnum::class,
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
