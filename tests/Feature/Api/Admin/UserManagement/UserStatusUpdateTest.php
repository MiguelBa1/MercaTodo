<?php

namespace Tests\Feature\Api\Admin\UserManagement;

use Tests\Feature\Utilities\UserTestCase;

class UserStatusUpdateTest extends UserTestCase
{
    /**
     * @test
     * Update the user status
     */
    public function testUpdateStatusUpdatesUserStatus(): void
    {
        // User status is active
        $response = $this->actingAs($this->adminUser)->patch(
            route('admin.api.update.user.status', $this->customerUser->id)
        );
        $this->customerUser->refresh();
        $response->assertStatus(200);
        $this->assertEquals('Inactive', $this->customerUser->status);

        // User status is inactive
        $response = $this->actingAs($this->adminUser)->patch(
            route('admin.api.update.user.status', $this->customerUser->id)
        );
        $this->customerUser->refresh();
        $response->assertStatus(200);
        $this->assertEquals('Active', $this->customerUser->status);
    }

}
