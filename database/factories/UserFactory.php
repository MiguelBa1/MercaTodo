<?php

namespace Database\Factories;

use App\Enums\DocumentTypeEnum;
use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected Str $str;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $this->str = new Str();

        return [
            'name' => fake()->firstName(),
            'surname' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'document' => fake()->unique()->numberBetween(
                1000000000,
                9999999999
            ),
            'document_type' => fake()->randomElement(array_column(DocumentTypeEnum::cases(), 'value')),
            'phone' => $this->generateColombianPhoneNumber(),
            'address' => fake()->address(),
            'city_id' => fake()->randomElement(City::query()->select('id')->get())->id,
            'status' => '1',
            'email_verified_at' => now(),
            'remember_token' => $this->str->random(10),
        ];
    }

    /**
     * Generate a Colombian phone number.
     *
     * @return string
     */
    private function generateColombianPhoneNumber(): string
    {
        $prefixes = [
            '301',
            '302',
            '304',
            '305',
            '310',
            '311',
            '312',
            '313',
            '314',
            '315',
            '316',
            '317',
            '318',
            '320',
            '321',
            '322',
            '323',
            '350',
            '351',
            '352',
            '353',
            '314',
            '315',
            '316',
            '317',
            '318',
            '320',
            '321',
            '322',
            '323',
            '350',
            '351',
            '352',
            '353'
        ];
        $prefix = $this->faker->randomElement($prefixes);
        $number = $this->faker->numberBetween(1000000, 9999999);

        return $prefix . $number;
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
