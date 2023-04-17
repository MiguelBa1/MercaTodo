<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use \App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /**
         * Insert the cities, states and roles in the database
         */
        $this->call([
            DepartmentsCitiesSeeder::class,
            RolesSeeder::class
        ]);

        User::factory(5)->create()->each(function ($user) {
            $user->assignRole('customer');
        });

//      Crea un usuario administrador
        User::factory()->create([
            'name' => env('ADMIN_NAME'),
            'document_type' => env('ADMIN_DOCUMENT_TYPE'),
            'document' => env('ADMIN_DOCUMENT'),
            'email' => env('ADMIN_EMAIL'),
            'phone' => env('ADMIN_PHONE'),
            'address' => env('ADMIN_ADDRESS'),
            'password' => bcrypt(env('ADMIN_PASSWORD')),
            'city_id' => env('ADMIN_CITY_ID'),
        ])->assignRole('admin');

    }
}
