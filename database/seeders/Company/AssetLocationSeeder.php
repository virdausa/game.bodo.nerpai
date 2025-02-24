<?php

namespace Database\Seeders\Company;

use App\Models\Company\AssetLocation;
use Illuminate\Database\Seeder;

class AssetLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $asset_location = [
            [
                'id' => 1,
                'type' => 'Office',
                'name' => 'Kantor',
                'description' => 'Seeder',
            ],
            [
                'id' => 2,
                'type' => 'Room',
                'name' => 'Ruang Kerja 1',
                'parent_id' => 1,
                'description' => 'Seeder',
            ],
            [
                'id' => 3,
                'type' => 'Room',
                'name' => 'Ruang Kerja 2',
                'parent_id' => 1,
                'description' => 'Seeder',
            ],
            [
                'id' => 4,
                'type' => 'Parking',
                'name' => 'Parkiran Utara',
                'description' => 'Seeder',
            ],
            [
                'id' => 5,
                'type' => 'Store',
                'name' => 'Toko Blitar',
                'description' => 'Seeder',
            ],
            [
                'id' => 6,
                'type' => 'Room',
                'name' => 'Ruang Teras Depan',
                'parent_id' => 5,
                'description' => 'Seeder',
            ]
        ];
    }
}
