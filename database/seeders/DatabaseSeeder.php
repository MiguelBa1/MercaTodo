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
        $role = new Role();

        $adminRole = $role->create(['name' => 'admin']);
        $customerRole = $role->create(['name' => 'customer']);

        User::factory(5)->create()->each(function ($user) use ($customerRole) {
            $user->assignRole($customerRole);
        });

//      Crea un usuario administrador
        $admin = User::factory()->create([
            'name' => config('app.admin_name'),
            'email' => config('app.admin_email'),
            'password' => bcrypt(config('app.admin_password')),
        ]);

        $admin->assignRole($adminRole);

    }
}
