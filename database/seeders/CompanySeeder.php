<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $CompanyUser1 = CompanyUser::create([
            'user_id' => '1',
            'user_type' => 'admin',
            'status' => 'approved',
        ]);

        $this->call([
            ExpeditionsTableSeeder::class,
            CompanyPermissionSeeder::class,
            EmployeesSeeder::class,
        ]);
    }
}
