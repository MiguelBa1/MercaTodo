<?php

namespace Tests\Feature\Middleware;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Utilities\UserTestCase;

class CheckUserStatusTest extends UserTestCase
{
    use RefreshDatabase;

    public function testRedirectsIfUserIsNotActive()
    {
        $this->actingAs($this->customerUser);

        $this->customerUser->update(['status' => false]);

        $this->get(route('profile.edit'))
            ->assertRedirect('/');
    }

    public function testContinueIfUserIsActive()
    {
        $this->actingAs($this->customerUser);

        $this->get(route('profile.edit'))
            ->assertOk();
    }
}
