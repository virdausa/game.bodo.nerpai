<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $store1 = Store::create([
            'name' => 'Toko A',
            'code' => 'store_a',
            'address' => json_encode([
                'street' => 'Jl. A',
                'city' => 'Kota A',
                'province' => 'Provinsi A',
                'country' => 'Negara A',
                'postal_code' => '12345',
            ]),
            'notes' => 'Seeder',
        ]);

        $store2 = Store::create([
            'name' => 'Toko B',
            'code' => 'store_b',
            'address' => json_encode([
                'street' => 'Jl. B',
                'city' => 'Kota B',
                'province' => 'Provinsi B',
                'country' => 'Negara B',
                'postal_code' => '12345',   
            ]),
            'notes' => 'Seeder',
        ]);
    }
}
