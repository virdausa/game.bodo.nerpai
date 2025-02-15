<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouse1 = Warehouse::create([
            'name' => 'Warehouse 1',
            'address' => json_encode([
                'street' => 'Jl. Contoh 1',
                'city' => 'Contoh Kota',
                'province' => 'Contoh Provinsi',
                'country' => 'Contoh Negara',
                'postal_code' => '12345',
            ]),
            'status' => 'Active',
            'notes' => 'Seeder',
        ]);

        $warehouse2 = Warehouse::create([
            'name' => 'Warehouse 2',
            'address' => json_encode([
                'street' =>  'Jl. Contoh 2',
                'city' => 'Contoh Kota',
                'province' => 'Contoh Provinsi',
                'country' => 'Contoh Negara',
                'postal_code' => '12345',
            ]),
            'status' => 'Active',
            'notes' => 'Seeder',
        ]);

        for ($room = 1; $room <= 3; $room++) {
            for ($rack = 1; $rack <= 2; $rack++) {
                Location::create([
                    'warehouse_id' => $warehouse1->id,
                    'room' => 'Room ' . $room,
                    'rack' => 'Rack ' . $rack,
                    'notes' => 'Seeder',
                ]);
                Location::create([
                    'warehouse_id' => $warehouse2->id,
                    'room' => 'Room ' . $room,
                    'rack' => 'Rack ' . $rack,
                    'notes' => 'Seeder',
                ]);
            }
        }
    }
}
