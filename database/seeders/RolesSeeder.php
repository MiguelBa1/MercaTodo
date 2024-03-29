<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Enums\RoleEnum;
use App\Enums\PermissionEnum;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** @var Role $superAdmin */
        $superAdmin = Role::create(['name' => RoleEnum::SUPER_ADMIN->value]);
        $superAdmin->syncPermissions(array_column(PermissionEnum::cases(), 'value'));

        Role::create(['name' => RoleEnum::ADMIN->value]);

        Role::create(['name' => RoleEnum::CUSTOMER->value]);
    }
}
