<?php

namespace Tests\Feature\Requests\Admin;

use Tests\Feature\Utilities\UserTestCase;

class ProfileUpdateRequestTest extends UserTestCase
{
    /**
     * @dataProvider invalidUserData
     * @param string $field
     * @param string|int $value
     * @param string $message
     * @return void
     */
    public function testUpdateProfileValidationFails(string $field, string|int $value, string $message): void
    {
        $response = $this->actingAs($this->adminUser)->patch(
            route('api.admin.users.profile.update', $this->customerUser->getAttribute('id')),
            [$field => $value]
        );

        $response->assertSessionHasErrors($field, $message);
        $response->assertRedirect();
        $this->assertDatabaseMissing('users', [
            'id' => $this->customerUser->getAttribute('id'),
            $field => $value,
        ]);
    }

    public static function invalidUserData(): array
    {
        return [
            'name is required' => ['name', '', 'The name field is required.'],
            'name is too long' => ['name', str_repeat('a', 101), 'The name may not be greater than 101 characters.'],
            'document is required' => ['document', '', 'The document field is required.'],
            'document should be a integer' => ['document', 'invalid', 'The document must be an integer.'],
            'document digits should be between 6 and 12' => [
                'document',
                1234567890123,
                'The document must be between 6 and 12 digits.'
            ],
            'document_type is required' => ['document_type', '', 'The document type field is required.'],
            'city_id is required' => ['city_id', '', 'The city id field is required.'],
            'city_id is invalid' => ['city_id', 'invalid', 'The selected city id is invalid.'],
            'phone is required' => ['phone', '', 'The phone field is required.'],
            'phone is too long' => ['phone', str_repeat('a', 256), 'The phone may not be greater than 255 characters.'],
            'address is required' => ['address', '', 'The address field is required.'],
            'address is too long' => [
                'address',
                str_repeat('a', 101),
                'The address may not be greater than 100 characters.'
            ],
        ];
    }
}
