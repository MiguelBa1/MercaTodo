<?php

namespace Tests\Feature\Web\Admin;

use Inertia\Testing\AssertableInertia;
use Tests\Feature\Utilities\UserTestCase;

class UserManagementTest extends UserTestCase
{
    public function testIndexRendersCorrectView(): void
    {
        $response = $this
            ->actingAs($this->adminUser)
            ->get(route('admin.view.users'));

        $response->assertOk();
        $response->assertStatus(200);
        $response->assertInertia(
            fn (AssertableInertia $page) => $page
            ->component('Admin/Users/Index')
        );
    }

    public function testEditRendersCorrectView(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.edit.user', $this->customerUser->id));
        $response->assertStatus(200);
        $response->assertInertia(
            fn (AssertableInertia $page) => $page
            ->component('Admin/Users/Edit')->has(
                'user',
                fn (AssertableInertia $page) => $page
                ->where('id', $this->customerUser->id)
                ->where('name', $this->customerUser->name)
                ->where('email', $this->customerUser->email)
                ->where('role_name', $this->customerUser->roles->first()->name)
                ->etc()
            )
        );
    }
}
