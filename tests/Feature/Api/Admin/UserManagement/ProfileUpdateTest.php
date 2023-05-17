<?php

namespace Tests\Feature\Api\Admin\UserManagement;

use App\Models\User;
use Tests\Feature\Utilities\UserTestCase;

class ProfileUpdateTest extends UserTestCase
{
    /**
     * @test
     * Update the user profile
     * @return void
     */
    public function testUpdateProfileUpdatesUserProfile(): void
    {
        $newName = 'John Doe';
        $newRole = 'admin';
        $newDocument = '123456789';
        $newDocumentType = 'Passport';
        $newCityId = 1;
        $newPhone = '123456789';
        $newAddress = '1234 Example Street';

        $response = $this->actingAs($this->adminUser)->patch(
            route('admin.api.users.profile.update', $this->customerUser->id),
            [
                'name' => $newName,
                'role_name' => $newRole,
                'document' => $newDocument,
                'document_type' => $newDocumentType,
                'city_id' => $newCityId,
                'phone' => $newPhone,
                'address' => $newAddress,
            ]
        );
        $response->assertStatus(200);
        $this->assertEquals($newName, $this->customerUser->fresh()->name);
        $this->assertEquals($newDocument, $this->customerUser->fresh()->document);
        $this->assertEquals($newDocumentType, $this->customerUser->fresh()->document_type);
        $this->assertEquals($newCityId, $this->customerUser->fresh()->city_id);
        $this->assertEquals($newPhone, $this->customerUser->fresh()->phone);
        $this->assertEquals($newAddress, $this->customerUser->fresh()->address);
        $this->assertTrue($this->customerUser->fresh()->hasRole($newRole));
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

    // Test document unique validation
    public function testUpdateProfileFailWithOtherUserDocument()
    {
        $secondCustomerUser = User::factory()->create();
        $response = $this->actingAs($this->adminUser)->patch(
            route('admin.api.users.profile.update', $this->customerUser->id),
            [
                'name' => $this->customerUser->getAttribute('name'),
                'role_name' => $this->customerUser->roles->first()->name,
                'document' => $secondCustomerUser->document,
                'document_type' => $this->customerUser->getAttribute('document_type'),
                'city_id' => $this->customerUser->getAttribute('city_id'),
                'phone' => $this->customerUser->getAttribute('phone'),
                'address' => $this->customerUser->getAttribute('address'),
            ]
        );
        $response->assertStatus(302);
        $response->assertSessionHasErrors('document');
    }
}
