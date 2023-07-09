<?php

namespace Tests\Feature\Api\Login;

use App\Models\City;
use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
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
    }

    public function testUserCanLogin(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->post(route('api.login'), [
            'email' => $user->email,
            'password' => 'password',
        ], ['accept' => 'application/json']);

        $response->assertStatus(200);
        $response->assertJsonMissingValidationErrors(['email', 'password']);
        $response->assertJsonStructure([
            'access_token',
        ]);
    }

    public function testCanValidateWrongPassword(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->post(route('api.login'), [
            'email' => $user->email,
            'password' => 'wrong password',
        ], ['accept' => 'application/json']);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'message' => trans('auth.failed'),
        ]);
    }

    public function testCanValidateWhenLogin(): void
    {
        $response = $this->post(route('api.login'), [
            'email' => 'email@',
            'password' => '',
        ], ['accept' => 'application/json']);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email', 'password']);
    }
}
