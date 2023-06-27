<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(5)->create()->each(function ($user) {
            $user->assignRole('customer');
        });

        // Create the admin user
        User::factory()->create([
            'name' => env('ADMIN_NAME'),
            'surname' => env('ADMIN_SURNAME'),
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
