<?php

namespace Tests\Feature\Api\Admin\Users;

use Illuminate\Support\Facades\Hash;
use Tests\Feature\Utilities\UserTestCase;

class PasswordUpdateTest extends UserTestCase
{
    public function testUpdatePasswordUpdatesUserPassword(): void
    {
        $newPassword = 'password123';
        $response = $this->actingAs($this->adminUser)
            ->patch(
                route('api.admin.users.password.update', $this->customerUser->getAttribute('id')),
                ['password' => $newPassword, 'password_confirmation' => $newPassword]
            );
        $response->assertOk();
        $this->assertTrue(Hash::check($newPassword, $this->customerUser->fresh()->password));
        $this->assertDatabaseHas('users', [
            'id' => $this->customerUser->getAttribute('id'),
            'password' => $this->customerUser->fresh()->password,
        ]);
    }
}
