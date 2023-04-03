<?php

namespace Tests\Feature\Api\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserControllerTest extends TestCase
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

    /**
     * @test
     * Update the user status
     */
    public function testUpdateStatusUpdatesUserStatus(): void
    {
        $response = $this->actingAs($this->adminUser)->patch(
            route('admin.api.update.user.status', $this->customerUser->id)
        );
        $response->assertStatus(200);
        $this->assertEquals(!$this->customerUser->status, $this->customerUser->fresh()->status);
    }

}
