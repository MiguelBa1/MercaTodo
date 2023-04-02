<?php

namespace Tests\Feature\Web\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    public function setUp(): void
    {
        parent::setUp();

        $role = new Role();
        $adminRole = $role->create(['name' => 'admin']);

        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole($adminRole);
    }

    /**
     * @test
     * Render the admin dashboard view
     */
    public function testAdminDashboardRendersCorrectView(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin');
        $response->assertStatus(200);
        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Admin/Dashboard'));
    }
}
