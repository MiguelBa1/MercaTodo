<?php

namespace Tests\Feature\Api\Admin\UserManagement;

use Illuminate\Support\Facades\Hash;
use Tests\Feature\Utilities\UserTestCase;

class PasswordUpdateTest extends UserTestCase
{
    /**
     * @test
     * Update the user password
     */
    public function testUpdatePasswordUpdatesUserPassword(): void
    {
        $newPassword = 'password123';
        $response = $this->actingAs($this->adminUser)
            ->patch(
                route('admin.api.users.password.update', $this->customerUser->id),
                ['password' => $newPassword, 'password_confirmation' => $newPassword]
            );
        $response->assertStatus(200);
        $this->assertTrue(Hash::check($newPassword, $this->customerUser->fresh()->password));
    }
}
