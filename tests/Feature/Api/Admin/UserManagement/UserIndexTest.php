<?php

namespace Tests\Feature\Api\Admin\UserManagement;

use Tests\Feature\Utilities\UserTestCase;

class UserIndexTest extends UserTestCase
{
    /**
     * @test
     * List all users with their roles except the current user
     */
    public function testListReturnsJson(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.api.users.index'));
        $response->assertStatus(200);
        $response->assertJsonStructure(['data', 'links']);
        $response->assertJsonFragment(['name' => $this->customerUser->name]);
    }
}
