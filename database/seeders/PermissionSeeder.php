<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Enums\PermissionEnum;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (PermissionEnum::cases() as $permission) {
            Permission::create(['name' => $permission->value]);
        }
    }
}
