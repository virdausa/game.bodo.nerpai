<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Supplier1 = Supplier::create([
            'name' => 'Supplier Anjing',
            'email' => 'anjing@gmail.com',
            'address' => json_encode([
                'street'=>  'Jl. Supplier Ajing',
                'city'=> 'Contoh Kota',
                'province'=> 'Contoh Provinsi',
                'country'=> 'Contoh Negara',
                'postal_code'=> '12345',
            ]),
            'phone_number' => '08123456789',
            'status' => 'Active',
            'notes' => 'Seeder',
        ]);

        $Supplier2 = Supplier::create([
            'name' => 'Supplier Babi',
            'email' => 'baby@gmail.com',
            'address' => json_encode([
                'street'=>  'Jl. Supplier Baby',
                'city'=> 'Contoh Kota',
                'province'=> 'Contoh Provinsi',
                'country'=> 'Contoh Negara',
                'postal_code'=> '12345',
            ]),
            'phone_number' => '081298763543',
            'status' => 'Active',
            'notes' => 'Seeder',
        ]);

        $Supplier3 = Supplier::create([
            'name' => 'Supplier Cat',
            'email' => 'cat@gmail.com',
            'address' => json_encode([
                'street'=>  'Jl. Supplier Cat',
                'city'=> 'Contoh Kota',
                'province'=> 'Contoh Provinsi',
                'country'=> 'Contoh Negara',
                'postal_code'=> '12345',
            ]),
            'phone_number' => '081295663543',
            'status' => 'Active',
            'notes' => 'Seeder',
        ]);
    }
}
