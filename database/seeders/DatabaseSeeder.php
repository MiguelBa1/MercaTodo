<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Reset the cache
        Cache::flush();

        // Reset the storage
        Storage::disk('public')->deleteDirectory('images');
        Storage::disk('public')->makeDirectory('images');

        $this->call([
            DepartmentsCitiesSeeder::class,
            RolesSeeder::class,
            UsersSeeder::class,
            BrandsSeeder::class,
            CategoriesSeeder::class,
            ProductsSeeder::class,
        ]);
    }
}
