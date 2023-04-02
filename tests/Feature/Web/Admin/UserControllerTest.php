<?php

namespace Tests\Feature\Web\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
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

    public function testIndexRendersCorrectView(): void
    {
        $response = $this
            ->actingAs($this->adminUser)
            ->get(route('admin.view.users'));

        $response->assertOk();
        $response->assertStatus(200);
        $response->assertInertia(fn(AssertableInertia $page) => $page
            ->component('Admin/Users/Index')
        );
    }

    public function testEditRendersCorrectView(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.edit.user', $this->customerUser->id));
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
}
