<?php

namespace Database\Seeders\Store;

use Illuminate\Database\Seeder;

use App\Models\Store\StoreRole;
use App\Models\Store\StorePermission;

class StoreRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define permissions
        $store_permissions = [
            'store-restocks sidebar',

            'store-customers sidebar',
            'store-pos sidebar',
            'sales sidebar',

            'products sidebar',
            'store-warehouses sidebar',
            'store-inbounds sidebar',
            'store-outbounds sidebar',

            'store-employees sidebar',

            'store-roles sidebar',
            'store-permissions sidebar',
        ];

        // Create permissions
        foreach ($store_permissions as $permission) {
            StorePermission::firstOrCreate(['name' => $permission, 'guard_name' => 'store']);
        }

        // Define roles and their permissions
        $store_roles = [
            'Store Manager' => [
                '1',
                '2',
                '3',
                '4',
                '5',
                '6',
                '7',
                '8',
                '9',
                '10',
                '11',
            ],
            'Asisten Manager' => [
                '1',
                '2',
                '3',
                '4',
                '5',
                '6',
                '7',
                '8',
                '9',
                '10',
                '11',
            ],
        ];

        
        // Create roles and assign permissions 
        foreach ($store_roles as $roleName => $rolePermissions) {
            $role = StoreRole::firstOrCreate(['name' => $roleName, 'guard_name' => 'store']);
            $role->permissions()->attach($rolePermissions);
        }
    }
}
