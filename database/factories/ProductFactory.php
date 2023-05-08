<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

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
        $image = Image::canvas(800, 800)->fill($this->getRandomColor())->encode('jpg');
        $imageName = Str::random(40) . '.jpg';

        Storage::disk('public')->put("images/{$imageName}", $image);

        return [
            'sku' => fake()->ean8(),
            'name' => fake()->sentence(2),
            'description' => fake()->text(),
            'price' => fake()->randomFloat(2, 1, 1000),
            'stock' => fake()->numberBetween(1, 100),
            'status' => fake()->boolean(),
            'brand_id' => Brand::query()->inRandomOrder()->first()->getAttribute('id'),
            'category_id' => Category::query()->inRandomOrder()->first()->getAttribute('id'),
            'image' => $imageName
        ];
    }

    private function getRandomColor(): string
    {
        $red = mt_rand(0, 255);
        $green = mt_rand(0, 255);
        $blue = mt_rand(0, 255);

        return "rgb({$red}, {$green}, {$blue})";
    }
}
