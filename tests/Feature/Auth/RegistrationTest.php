<?php

namespace Tests\Feature\Auth;

use App\Enums\DocumentTypeEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class RegistrationTest extends BaseTestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $role = new Role();
        $role->create(['name' => 'customer']);
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'document' => '123456789',
            'document_type' => DocumentTypeEnum::PASSPORT->value,
            'phone' => '123456789',
            'address' => 'Test Address',
            'city_id' => 1
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('verification.notice'));
    }
}
