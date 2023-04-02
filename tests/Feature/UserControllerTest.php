<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Inertia\Testing\AssertableInertia;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $customerUser;
    protected User $adminUser;
    protected static Hash $hasher;

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

    public function testIndexRendersCorrectView()
    {
        $response = $this
            ->actingAs($this->adminUser)
            ->get(route('admin.users'));

        $response->assertOk();
        $response->assertStatus(200);
        $response->assertInertia(fn(AssertableInertia $page) => $page
            ->component('Admin/Users/Index')
        );
    }

    public function testListReturnsJson()
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.list-users'));
        $response->assertStatus(200);
        $response->assertJsonStructure(['data', 'links']);
    }

    public function testManageStatusUpdatesUserStatus()
    {
        $response = $this->actingAs($this->adminUser)->patch(
            route('admin.manage-user-status', $this->customerUser->id)
        );
        $response->assertStatus(200);
        $this->assertEquals(!$this->customerUser->status, $this->customerUser->fresh()->status);
    }

    public function testEditRendersCorrectView()
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.edit-user', $this->customerUser->id));
        $response->assertStatus(200);
        $response->assertInertia(fn(AssertableInertia $page) => $page
            ->component('Admin/Users/Edit')->has('user', fn(AssertableInertia $page) => $page
                ->where('id', $this->customerUser->id)
                ->where('name', $this->customerUser->name)
                ->where('email', $this->customerUser->email)
                ->where('role_name', $this->customerUser->roles->first()->name)
                ->etc()
            )
        );
    }

    public function testUpdatePasswordUpdatesUserPassword()
    {
        $newPassword = 'newpassword123';
        $response = $this->actingAs($this->adminUser)
            ->patch(
                route('admin.update-user-password', $this->customerUser->id),
                ['password' => $newPassword, 'password_confirmation' => $newPassword]
            );
        $response->assertStatus(200);
        $this->assertTrue(Hash::check($newPassword, $this->customerUser->fresh()->password));
    }

    public function testUpdateProfileUpdatesUserProfile()
    {
        $newName = 'John Doe';
        $newEmail = 'johndoe@example.com';
        $newRole = 'admin';
        $response = $this->actingAs($this->adminUser)->patch(
            route('admin.update-user-profile', $this->customerUser->id),
            ['name' => $newName, 'role_name' => $newRole]
        );
        $response->assertStatus(200);
        $this->assertEquals($newName, $this->customerUser->fresh()->name);
        $this->assertTrue($this->customerUser->fresh()->hasRole($newRole));
    }
}
