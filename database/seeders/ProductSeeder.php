<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product1 = Product::create([
            'sku' => 'A-01',
            'name' => 'Product A',
            'price' => 10000,
            'weight' => 500,
            'status' => 'Active',
            'notes' => 'Seeder',
        ]);

        $product2 = Product::create([
            'sku' => 'B-01',
            'name' => 'Product B',
            'price' => 20000,
            'weight' => 1000,
            'status' => 'Active',
            'notes' => 'Seeder',
        ]);

        $product3 = Product::create([
            'sku' => 'C-01',
            'name' => 'Product C',
            'price' => 30000,
            'weight' => 3000,
            'status' => 'Active',
            'notes' => 'Seeder',
        ]);
    }
}
