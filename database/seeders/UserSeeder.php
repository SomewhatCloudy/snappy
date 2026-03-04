<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.org',
            'password' => Hash::make('password'),
        ]);

        // Regular Test User
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.org',
            'password' => Hash::make('password'),
        ]);
    }
}
