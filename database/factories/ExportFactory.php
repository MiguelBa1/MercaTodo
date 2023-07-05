<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\ExportStatusEnum;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Export>
 */
class ExportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'filename' => $this->faker->word,
            'status' => $this->faker->randomElement(array_column(ExportStatusEnum::cases(), 'value')),
            'type' => $this->faker->word,
            'error' => $this->faker->word,
        ];
    }
}
