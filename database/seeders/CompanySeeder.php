<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            // CompanyUserSeeder::class,
            // CompanyPermissionSeeder::class,
            // EmployeesSeeder::class,
            // ProductSeeder::class,
            // WarehouseSeeder::class,
            // CustomerSeeder::class,
            // SupplierSeeder::class,
            // ExpeditionsTableSeeder::class,
            AccountSeeder::class,
        ]);
    }
}
