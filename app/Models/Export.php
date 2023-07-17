<?php

namespace App\Models;

use App\Enums\ExportStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Export
 *
 * @property int $id
 * @property string $filename
 * @property ExportStatusEnum $status
 * @property string $error
 * @property string $type
 */
class Export extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'status',
        'error',
        'type'
    ];

    protected $cast = [
        'status' => ExportStatusEnum::class,
    ];
}
