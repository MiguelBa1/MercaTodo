<?php

namespace Tests\Feature\Web;

use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    public function testHomeRendersCorrectView(): void
    {
        $response = $this->get(route('home'));
        $response->assertOk();
        $response->assertStatus(200);
        $response->assertInertia(
            fn (AssertableInertia $page) => $page
            ->component('Home')
        );
    }
}
