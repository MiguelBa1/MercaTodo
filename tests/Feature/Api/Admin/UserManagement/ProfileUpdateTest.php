<?php

namespace Tests\Feature\Api\Admin\UserManagement;

use App\Models\User;
use Tests\Feature\Utilities\UserTestCase;

class ProfileUpdateTest extends UserTestCase
{
    /**
     * @dataProvider validUserData
     * @param array $userData
     * @return void
     */
    public function testUpdateProfileUpdatesUserProfile(array $userData): void
    {
        $response = $this->actingAs($this->adminUser)->patch(
            route('admin.api.users.profile.update', $this->customerUser->getAttribute('id')),
            $userData
        );
        $response->assertOk();
        $this->assertDatabaseHas('users', [
            'id' => $this->customerUser->getAttribute('id'),
            'name' => $userData['name'],
            'document' => $userData['document'],
            'document_type' => $userData['document_type'],
            'city_id' => $userData['city_id'],
            'phone' => $userData['phone'],
            'address' => $userData['address'],
        ]);

        // test the user role
        $this->assertDatabaseHas('model_has_roles', [
            'role_id' => $this->customerUser->roles->first()->id,
            'model_id' => $this->customerUser->getAttribute('id'),
            'model_type' => 'App\Models\User',
        ]);
    }

    // Test update user profile with the old document
    public function testUpdateProfileUpdatesUserProfileWithOldDocument()
    {
        $oldDocument = $this->customerUser->document;

        $response = $this->actingAs($this->adminUser)->patch(
            route('admin.api.users.profile.update', $this->customerUser->id),
            [
                'name' => $this->customerUser->getAttribute('name'),
                'role_name' => $this->customerUser->roles->first()->name,
                'document' => $oldDocument,
                'document_type' => $this->customerUser->getAttribute('document_type'),
                'city_id' => $this->customerUser->getAttribute('city_id'),
                'phone' => $this->customerUser->getAttribute('phone'),
                'address' => $this->customerUser->getAttribute('address'),
            ]
        );
        $response->assertStatus(200);
        $this->assertEquals($oldDocument, $this->customerUser->fresh()->document);
    }

    public static function validUserData(): array
    {
        return [
            'valid user data' => [
                [
                    'name' => 'John Doe',
                    'role_name' => 'customer',
                    'document' => '123456789',
                    'document_type' => 'Driver License',
                    'city_id' => 1,
                    'phone' => '123456789',
                    'address' => 'Calle 123',
                ]
            ],
        ];
    }
}
