<?php

namespace Tests\Feature\Api\Admin\UserManagement;

use Tests\Feature\Utilities\UserTestCase;

class ProfileUpdateTest extends UserTestCase
{
    /**
     * @test
     * Update the user profile
     * @return void
     */
    public function testUpdateProfileUpdatesUserProfile() : void
    {
        $newName = 'John Doe';
        $newEmail = 'johndoe@example.com';
        $newRole = 'admin';
        $response = $this->actingAs($this->adminUser)->patch(
            route('admin.api.update.user.profile', $this->customerUser->id),
            ['name' => $newName, 'role_name' => $newRole]
        );
        $response->assertStatus(200);
        $this->assertEquals($newName, $this->customerUser->fresh()->name);
        $this->assertTrue($this->customerUser->fresh()->hasRole($newRole));
    }
}
