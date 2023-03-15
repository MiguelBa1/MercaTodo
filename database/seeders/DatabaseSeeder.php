<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // $role = Role::where('name', 'admin')->first();
        // Crea un usuario administrador
        // \App\Models\User::factory()->create([
        //     'name' => 'admin',
        //     'email' => 'admin@mail.com',
        //     'password' => bcrypt('password'),
        //     'role_id' => $role->id,
        // ]);
    }
}
