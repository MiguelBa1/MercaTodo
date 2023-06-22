<?php

namespace Tests\Feature\Api\Admin\Users;

use Tests\Feature\Utilities\UserTestCase;

class UserStatusUpdateTest extends UserTestCase
{
    public function testAdminCanActivateAnUser(): void
    {
        $this->customerUser->setAttribute('status', false);
        $this->customerUser->save();

        $response = $this->actingAs($this->adminUser)->patch(
            route('admin.api.users.status.update', $this->customerUser->getAttribute('id'))
        );

        $this->customerUser->refresh();
        $response->assertOk();
        $this->assertEquals('Active', $this->customerUser->status);

        $this->assertDatabaseHas('users', [
            'id' => $this->customerUser->getAttribute('id'),
            'status' => true,
        ]);
    }

    public function testAdminCanDeactivateAnUser(): void
    {
        $this->customerUser->setAttribute('status', true);
        $this->customerUser->save();

        $response = $this->actingAs($this->adminUser)->patch(
            route('admin.api.users.status.update', $this->customerUser->getAttribute('id'))
        );

        $this->customerUser->refresh();
        $response->assertOk();
        $this->assertEquals('Inactive', $this->customerUser->status);

        $this->assertDatabaseHas('users', [
            'id' => $this->customerUser->getAttribute('id'),
            'status' => false,
        ]);
    }
}
