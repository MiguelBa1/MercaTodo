<?php

namespace Tests\Feature\Api\Admin\UserManagement;

use Tests\Feature\Utilities\UserTestCase;

class UserListTest extends UserTestCase
{
    /**
     * @test
     * List all users
     */
    public function testListReturnsJson(): void
    {

        $response = $this->actingAs($this->adminUser)->get(route('admin.api.list.users'));
        $response->assertStatus(200);
        $response->assertJsonStructure(['data', 'links']);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment(['name' => $this->customerUser->name]);
        $response->assertJsonFragment(['name' => $this->adminUser->name]);
    }

}
