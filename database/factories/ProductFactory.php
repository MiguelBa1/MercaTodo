<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sku' => fake()->ean8(),
            'name' => fake()->word(),
            'description' => fake()->text(),
            'price' => fake()->randomFloat(2, 1, 1000),
            'stock' => fake()->numberBetween(1, 100),
            'status' => fake()->boolean(),
            'brand_id' => Brand::query()->inRandomOrder()->first()->getAttribute('id'),
            'category_id' => Category::query()->inRandomOrder()->first()->getAttribute('id'),
            'image' => fake()->image('storage/app/public/images', 640, 480, null, false),
        ];
    }
}
