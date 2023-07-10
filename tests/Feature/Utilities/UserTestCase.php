<?php

namespace Tests\Feature\Utilities;

use App\Models\City;
use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use App\Enums\RoleEnum;
use Tests\TestCase;

class UserTestCase extends TestCase
{
    use RefreshDatabase;
    protected User $customerUser;
    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a Department and City to be able to create a User
        $testDepartment = Department::query()->create([
            'name' => 'Test Department',
        ]);
        City::query()->create([
            'name' => 'Test City',
            'department_id' => $testDepartment->id,
        ]);

        $adminRole = Role::query()->create(['name' => RoleEnum::ADMIN->value]);
        $customerRole = Role::query()->create(['name' => RoleEnum::CUSTOMER->value]);

        $this->customerUser = User::factory()->create();
        $this->customerUser->assignRole($customerRole);
        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole($adminRole);
    }
}
