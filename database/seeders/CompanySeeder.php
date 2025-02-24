<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Database\Seeders\Store\StoreRolePermissionSeeder;
use Database\Seeders\Company\MainSeeder;

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
            CompanyPermissionSeeder::class,
            EmployeesSeeder::class,
            ProductSeeder::class,
            WarehouseSeeder::class,
            CustomerSeeder::class,
            SupplierSeeder::class,
            AccountSeeder::class,

            // stores
            StoreRolePermissionSeeder::class,

            MainSeeder::class,
        ]);
    }
}
