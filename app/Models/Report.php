<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\ReportStatusEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Report
 *
 * @property int $id
 * @property string $report_type
 * @property array|null $data
 * @property ReportStatusEnum $status
 * @property int|null $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User|null $user
 */
class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_type',
        'data',
        'status',
        'user_id',
    ];

    protected $casts = [
        'data' => 'array',
        'status' => ReportStatusEnum::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
