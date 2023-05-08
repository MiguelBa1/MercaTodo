<?php
namespace Tests\Feature\Auth;

use App\Models\City;
use App\Models\Department;
use Tests\TestCase;

class BaseTestCase extends TestCase
{
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
    }
}
