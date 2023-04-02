<?php

namespace Tests\Feature\Api\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PasswordControllerTest extends TestCase
{
    use RefreshDatabase;
    protected User $customerUser;
    protected User $adminUser;

    public function setUp(): void
    {
        parent::setUp();

        $role = new Role();
        $adminRole = $role->create(['name' => 'admin']);
        $customerRole = $role->create(['name' => 'customer']);

        $this->customerUser = User::factory()->create();
        $this->customerUser->assignRole($customerRole);
        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole($adminRole);
    }

    /**
     * @test
     * Update the user password
     */
    public function testUpdatePasswordUpdatesUserPassword(): void
    {
        $newPassword = 'password123';
        $response = $this->actingAs($this->adminUser)
            ->patch(
                route('admin.api.update.user.password', $this->customerUser->id),
                ['password' => $newPassword, 'password_confirmation' => $newPassword]
            );
        $response->assertStatus(200);
        $this->assertTrue(Hash::check($newPassword, $this->customerUser->fresh()->password));
    }
}
