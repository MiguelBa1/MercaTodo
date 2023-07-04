<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Export
 *
 * @property int $id
 * @property string $filename
 * @property string $status
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
}
