<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\City
 *
 * @property int $id
 * @property string $name
 * @property int $department_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Department $department
 * @property-read Collection<int, User> $users
 */
class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'department_id',
    ];
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'city_id', 'id');
    }
}
