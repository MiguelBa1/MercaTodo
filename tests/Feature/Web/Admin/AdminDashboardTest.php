<?php

namespace Tests\Feature\Web\Admin;

use Inertia\Testing\AssertableInertia;
use Tests\Feature\Utilities\UserTestCase;

class AdminDashboardTest extends UserTestCase
{
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
