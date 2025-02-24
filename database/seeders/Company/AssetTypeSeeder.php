<?php

namespace Database\Seeders\Company;

use App\Models\Company\AssetType;
use Illuminate\Database\Seeder;

class AssetTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $asset_type = [
            [
                'name' => 'Tanah',
                'description' => 'Tanah',
            ],
            [
                'name' => 'Bangunan',
                'description' => 'Bangunan',
            ],
            [
                'name' => 'Kendaraan',
                'description' => 'Kendaraan',
            ],
            [
                'name' => 'Mesin dan Peralatan',
                'description' => 'Peralatan dan Mesin',
            ],
            [
                'name' => 'Aset Tak Berwujud',
                'description' => 'Aset Tak Berwujud',
            ],
        ];

        AssetType::insert($asset_type);
    }
}
