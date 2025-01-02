<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create an admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('adminpassword'), // Secure password
            'role' => 'admin', // Assign role as admin
        ]);

        // Create a regular user
        User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => bcrypt('userpassword'), // Secure password
            'role' => 'doctor', // Assign role as user
        ]);

        User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => bcrypt('userpassword'), // Secure password
            'role' => 'staff', // Assign role as user
        ]);

        // Optionally create additional random users
        User::factory(10)->create();
    }
}
