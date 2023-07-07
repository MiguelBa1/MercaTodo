<?php

namespace Database\Factories;

use App\Enums\ExportStatusEnum;
use App\Models\Import;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Import>
 */
class ImportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::all('id')->random(),
            'filename' => $this->faker->word,
            'status' => $this->faker->randomElement(array_column(ExportStatusEnum::cases(), 'value')),
        ];
    }
}
