<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $CompanyUser1 = CompanyUser::create([
            'user_id' => '1',
            'user_type' => 'admin',
            'status' => 'approved',
        ]);
    }
}
