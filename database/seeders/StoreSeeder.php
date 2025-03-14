<?php

namespace Database\Seeders;

use App\Models\Company\Store;
use Illuminate\Database\Seeder;

use Database\Seeders\Store\StoreRolePermissionSeeder;

use Database\Seeders\Store\StoreSeederCaller;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            StoreSeederCaller::class,
        ]);
    }
}
