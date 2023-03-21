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
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'customer']);

        User::factory(10)->create()->each(function ($user) use ($userRole) {
            $user->assignRole($userRole);
        });

//      Crea un usuario administrador
        $admin = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@mail.com',
            'password' => bcrypt('password'),
        ]);

        $admin->assignRole($adminRole);

        $testUser = User::factory()->create([
            'name' => 'miguel',
            'email' => 'miguel@mail.com',
            'password' => bcrypt('password'),
        ]);

        $testUser->assignRole($userRole);

    }
}
