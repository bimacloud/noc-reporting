<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        // NOC
        User::factory()->create([
            'name' => 'NOC User',
            'email' => 'noc@example.com',
            'role' => 'noc',
            'password' => bcrypt('password'),
        ]);

        // Manager
        User::factory()->create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'role' => 'manager',
            'password' => bcrypt('password'),
        ]);
        $this->call([
            MasterDataSeeder::class,
            MonitoringSeeder::class,
            CustomerIncidentSeeder::class,
            ServiceLogSeeder::class,
        ]);
    }
}
