<?php

namespace Tests\Feature\Api\Admin\UserManagement;

use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;
use Tests\Feature\Utilities\UserTestCase;

class RoleListTest extends UserTestCase
{
    /**
     * @test
     * List all roles
     * @return void
     */
    public function testListReturnsJson(): void
    {
        // Act
        $response = $this->actingAs($this->adminUser)->get(route('admin.api.roles.index'));

        // Assert
        $response->assertStatus(200);
        $this->assertInstanceOf(JsonResponse::class, $response->baseResponse);
        $this->assertEquals(Role::all()->pluck('name'), $response->baseResponse->original);
    }
}
