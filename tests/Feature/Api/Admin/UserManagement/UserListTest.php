<?php

namespace Tests\Feature\Api\Admin\UserManagement;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserListTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @test
     * List all users
     */
    public function testListReturnsJson(): void
    {
        $role = new Role();
        $adminRole = $role->create(['name' => 'admin']);
        $customerRole = $role->create(['name' => 'customer']);

        $customerUser = User::factory()->create();
        $customerUser->assignRole($customerRole);

        $adminUser = User::factory()->create();
        $adminUser->assignRole($adminRole);

        $response = $this->actingAs($adminUser)->get(route('admin.api.list.users'));
        $response->assertStatus(200);
        $response->assertJsonStructure(['data', 'links']);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonFragment(['name' => $customerUser->name]);
        $response->assertJsonFragment(['name' => $adminUser->name]);
    }

}
