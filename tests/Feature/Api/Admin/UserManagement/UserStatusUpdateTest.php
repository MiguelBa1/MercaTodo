<?php

namespace Tests\Feature\Api\Admin\UserManagement;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserStatusUpdateTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     * Update the user status
     */
    public function testUpdateStatusUpdatesUserStatus(): void
    {
        $role = new Role();
        $adminRole = $role->create(['name' => 'admin']);
        $customerRole = $role->create(['name' => 'customer']);

        $customerUser = User::factory()->create();
        $customerUser->assignRole($customerRole);

        $adminUser = User::factory()->create();
        $adminUser->assignRole($adminRole);

        // User status is active
        $response = $this->actingAs($adminUser)->patch(
            route('admin.api.update.user.status', $customerUser->id)
        );
        $customerUser->refresh();
        $response->assertStatus(200);
        $this->assertEquals('Inactive', $customerUser->status);

        // User status is inactive
        $response = $this->actingAs($adminUser)->patch(
            route('admin.api.update.user.status', $customerUser->id)
        );
        $customerUser->refresh();
        $response->assertStatus(200);
        $this->assertEquals('Active', $customerUser->status);
    }

}
