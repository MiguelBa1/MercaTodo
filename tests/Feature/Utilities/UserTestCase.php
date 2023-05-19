<?php

namespace Tests\Feature\Utilities;

use App\Models\City;
use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
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
        $testDepartment = Department::create([
            'name' => 'Test Department',
        ]);
        $testCity = City::create([
            'name' => 'Test City',
            'department_id' => $testDepartment->id,
        ]);

        $role = new Role();
        $adminRole = $role->create(['name' => 'admin']);
        $customerRole = $role->create(['name' => 'customer']);

        $this->customerUser = User::factory()->create();
        $this->customerUser->assignRole($customerRole);
        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole($adminRole);
    }
}
