<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Enums\RoleEnum;

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
        /** @var User $user */
        $user = User::factory()->create([
            'name' => config('user.admin.name'),
            'surname' => config('user.admin.surname'),
            'document_type' => config('user.admin.document_type'),
            'document' => config('user.admin.document'),
            'email' => config('user.admin.email'),
            'phone' => config('user.admin.phone'),
            'address' => config('user.admin.address'),
            'password' => bcrypt(config('user.admin.password')),
            'city_id' => config('user.admin.city_id'),
        ]);

        $user->assignRole(RoleEnum::SUPER_ADMIN->value);
    }
}
