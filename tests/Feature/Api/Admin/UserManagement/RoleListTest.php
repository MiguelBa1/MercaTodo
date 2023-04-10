<?php

namespace Tests\Feature\Api\Admin\UserManagement;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleListTest extends TestCase
{
    use RefreshDatabase;
    protected User $adminUser;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $role = new Role();
        $adminRole = $role->create(['name' => 'admin']);

        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole($adminRole);
    }

    /**
     * @test
     * List all roles
     * @return void
     */
    public function testListReturnsJson(): void
    {
        // Act
        $response = $this->actingAs($this->adminUser)->get(route('admin.api.list.roles'));

        // Assert
        $response->assertStatus(200);
        $this->assertInstanceOf(JsonResponse::class, $response->baseResponse);
        $this->assertEquals(Role::all()->pluck('name'), $response->baseResponse->original);
    }
}
