<?php

namespace Database\Seeders\Company;

use App\Models\Company\Courier;
use Illuminate\Database\Seeder;

class CourierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courirer1 = Courier::create([
            'code' => 'nex',
            'name' => 'Nerpai Express',
            'contact_info' => '08123456789',
            'website' => 'https://express.nerpai.space',
            'status' => 'active',
            'notes' => 'Seeder',
        ]);

        $courirer2 = Courier::create([
            'code' => 'internal',
            'name' => 'Kirim Sendiri',
            'contact_info' => '08123456789',
            'website' => '',
            'status' => 'active',
            'notes' => 'Seeder',
        ]);
    }
}
