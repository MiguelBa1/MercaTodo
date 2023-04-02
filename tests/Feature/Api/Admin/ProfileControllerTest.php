<?php

namespace Tests\Feature\Api\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
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
