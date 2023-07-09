<?php

namespace App\Models;

use App\Enums\ImportStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Import
 *
 * @property int $id
 * @property string $filename
 * @property int $user_id
 * @property ImportStatusEnum $status
 * @property array|null $errors
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 */
class Import extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'filename',
        'status',
        'errors',
    ];

    protected $casts = [
        'status' => ImportStatusEnum::class,
        'errors' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
