<?php

namespace Database\Seeders\Store;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreSeederCaller extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            StoreLocationSeeder::class,
        ]);
    }
}
