<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CompanyUser;

class CompanySeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            CompanyUserSeeder::class,
            // ExpeditionsTableSeeder::class,
            CompanyPermissionSeeder::class,
            EmployeesSeeder::class,
        ]);
    }
}
