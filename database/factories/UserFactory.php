<?php

namespace Database\Factories;

use App\Enums\DocumentTypeEnum;
use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $str;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $this->str = new Str();

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'document' => fake()->unique()->numberBetween(
                1000000000,
                9999999999
            ),
            'document_type' => DocumentTypeEnum::getRandomValue(),
            'phone' => fake()->numberBetween(1000000000, 9999999999),
            'address' => fake()->address(),
            'city_id' => fake()->randomElement(City::select('id')->get())->id,
            'status' => '1',
            'email_verified_at' => now(),
            'remember_token' => $this->str->random(10),
        ];
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
