<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Database\Seeders\Space\SpaceSeeder;

use Database\Seeders\Primary\PrimarySeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,

            SpaceSeeder::class,

            PrimarySeeder::class,
            //BackupUsersSeeder::class,
        ]);
    }
}
