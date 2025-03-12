<?php

namespace Database\Seeders;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Owner',
            'email' => 'owner@gmail.com',
            'role_id' => '3',
            'password' => Hash::make('owner123'),
        ]);

        $role = Role::findOrFail(3);
        $user->syncRoles($role);
    }
}
