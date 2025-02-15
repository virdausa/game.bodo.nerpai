<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customer1 = Customer::create([
            'name' => 'Customer Anjing',
            'email' => 'anjing@gmail.com',
            'address' => json_encode([
                'street'=>  'Jl. Customer Ajing',
                'city'=> 'Contoh Kota',
                'province'=> 'Contoh Provinsi',
                'country'=> 'Contoh Negara',
                'postal_code'=> '12345',
            ]),
            'phone_number' => '08123456789',
            'status' => 'Active',
            'notes' => 'Seeder',
        ]);

        $customer2 = Customer::create([
            'name' => 'Customer Babi',
            'email' => 'baby@gmail.com',
            'address' => json_encode([
                'street'=>  'Jl. Customer Baby',
                'city'=> 'Contoh Kota',
                'province'=> 'Contoh Provinsi',
                'country'=> 'Contoh Negara',
                'postal_code'=> '12345',
            ]),
            'phone_number' => '081298763543',
            'status' => 'Active',
            'notes' => 'Seeder',
        ]);

        $customer3 = Customer::create([
            'name' => 'Customer Cat',
            'email' => 'cat@gmail.com',
            'address' => json_encode([
                'street'=>  'Jl. Customer Cat',
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
