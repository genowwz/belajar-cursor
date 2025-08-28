<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed titles and statuses first
        $this->call([
            TitlesSeeder::class,
            StatusesSeeder::class,
        ]);

        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@complaints.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
            'title' => 'Veteran Complainer',
            'complaint_count' => 0,
        ]);

        // Create test user
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'is_admin' => false,
            'title' => 'Newcomer',
            'complaint_count' => 0,
        ]);
    }
}
