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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Owner',
            'email' => 'owner@gmail.com',
            'role' => 'admin',
            'password' => '$2y$12$yMFFZY8/jMOtJiz3jQAiUuBvYCwbMoUbkNINHoCWQzt/sILsr28oG',   // owner123
        ]);

        $this->call([
            // TODO: Create permission seeder
            // PermissionSeeder::class,
        ]);
    }
}
