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
            'first_name' => 'Admin',
            'middle_name' => 'Admin',
            'last_name' => 'Admin',
            'purok' => 'Purok 1',
            'barangay' => 'Tubigan',
            'city' => 'Initao',
            'phone' => '9123456789',
            'email' => 'admin@example.com',
            'password' => bcrypt('adminpassword'), // Secure password
            'role' => 'admin', // Assign role as admin
        ]);

        // Create a regular user
        // User::factory()->create([
        //     'name' => 'Regular User',
        //     'email' => 'doctor@example.com',
        //     'password' => bcrypt('doctorpassword'), // Secure password
        //     'role' => 'doctor', // Assign role as user
        // ]);

        // User::factory()->create([
        //     'name' => 'Regular User',
        //     'email' => 'staff@example.com',
        //     'password' => bcrypt('staffpassword'), // Secure password
        //     'role' => 'staff', // Assign role as user
        // ]);

        // Optionally create additional random users
        User::factory(10)->create();
    }
}
