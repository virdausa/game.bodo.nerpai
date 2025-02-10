<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CompanyPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define permissions
        $permissions = [
            'purchases sidebar',
            'inventory sidebar',
            'suppliers sidebar',
            'customers sidebar',
            'products sidebar',
            'purchases sidebar',
            'warehouses sidebar',
            'sales sidebar',
            'inbound-request sidebar',
            'outbound-request sidebar',
            'employees sidebar',
            'user sidebar',
            'role-access sidebar',
            'permissions sidebar',
            
            'crud role-access',
            'team-only role-access',

            'crud user',

            'crud employee',

            'crud supplier',

            'crud inbound-request',
            'show-only inbound-request',

            'crud outbound-request',
            'show-only outbound-request',

            'crud warehouse',
            'show-only warehouse',

            'crud inventory',
            'show-only inventory',

            'crud product',
            'show-only product',

            'crud location',
            'show-only location',

            'crud customer',

            'crud customer-complaint',
            'show-only customer-complaint',

            'crud sales',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Define roles and their permissions
        $roles = [
            'Owner' => [
                'purchases sidebar',
                'suppliers sidebar',
                'customers sidebar',
                'products sidebar',
                'purchases sidebar',
                'warehouses sidebar',
                'sales sidebar',
                'inbound-request sidebar',
                'outbound-request sidebar',
                'employees sidebar',
                'user sidebar',
                'role-access sidebar',
                'permissions sidebar',
                'crud user',
                'crud employee',
                'crud supplier',
                'crud inbound-request',
                'crud warehouse',
                'crud role-access',
                'crud outbound-request',
                'crud inventory',
                'crud product',
                'crud location',
                'crud customer',
                'crud customer-complaint',
                'crud sales',
            ],
            'General Manager' => [
                'purchases sidebar',
                'suppliers sidebar',
                'customers sidebar',
                'products sidebar',
                'purchases sidebar',
                'warehouses sidebar',
                'sales sidebar',
                'inbound-request sidebar',
                'outbound-request sidebar',
                'employees sidebar',
                'user sidebar',
                'role-access sidebar',
                'permissions sidebar',
                'crud user',
                'crud employee',
                'crud supplier',
                'crud inbound-request',
                'crud warehouse',
                'crud role-access',
                'crud outbound-request',
                'crud inventory',
                'crud product',
                'crud location',
                'crud customer',
                'crud customer-complaint',
                'crud sales',
            ],
            'Data Master' => [
               'purchases sidebar',
                'suppliers sidebar',
                'customers sidebar',
                'products sidebar',
                'purchases sidebar',
                'warehouses sidebar',
                'sales sidebar',
                'inbound-request sidebar',
                'outbound-request sidebar',
                'employees sidebar',
                'user sidebar',
                'role-access sidebar',
                'permissions sidebar',
                'crud user',
                'crud employee',
                'crud supplier',
                'crud inbound-request',
                'crud warehouse',
                'crud role-access',
                'crud outbound-request',
                'crud inventory',
                'crud product',
                'crud location',
                'crud customer',
                'crud customer-complaint',
                'crud sales',
            ],
            'Purchase Manager' => [
                 'purchases sidebar',
                 'suppliers sidebar',
                 'products sidebar',
                 'purchases sidebar',
                 'warehouses sidebar',
                 'inbound-request sidebar',
                 'role-access sidebar',

                 'crud supplier',
                 'show-only inbound-request',
                 'show-only warehouse',
                 'team-only role-access',
                 'show-only inventory',
                 'show-only product',
                 'show-only location',
             ],

             'Purchase Team' => [
                 'purchases sidebar',
                 'suppliers sidebar',
                 'products sidebar',
                 'purchases sidebar',
                 'warehouses sidebar',
                 'inbound-request sidebar',

                 'crud supplier',
                 'show-only inbound-request',
                 'show-only warehouse',
                 'show-only inventory',
                 'show-only product',
                 'show-only location',
             ],

             'Warehouse Manager' => [
                 'purchases sidebar',
                 'outbound-request sidebar',
                 'products sidebar',
                 'warehouses sidebar',
                 'inbound-request sidebar',
                 'role-access sidebar',

                 'crud inbound-request',
                 'crud warehouse',
                 'team-only role-access',
                 'crud inventory',
                 'crud location',
                 'show-only product',
                 'show-only customer-complaint',
                 'show-only outbound-request',
             ],
             'Warehouse Team' => [
                 'purchases sidebar',
                 'outbound-request sidebar',
                 'products sidebar',
                 'warehouses sidebar',
                 'inbound-request sidebar',

                 'crud inbound-request',
                 'crud warehouse',
                 'crud location',
                 'crud inventory',
                 'show-only product',
                 'show-only customer-complaint',
                 'show-only outbound-request',
             ],

             'Sales Manager' => [
                 'purchases sidebar',
                 'customers sidebar',
                 'sales sidebar',
                 'outbound-request sidebar',
                 'products sidebar',
                 'warehouses sidebar',
                 'inbound-request sidebar',
                 'role-access sidebar',

                 'show-only warehouse',
                 'show-only inventory',
                 'team-only role-access',
                 'crud inventory',
                 'crud customer',
                 'crud customer-complaint',
                 'crud sales',
                 'show-only product',
                 'show-only location',
                 'show-only customer-complaint',
                 'show-only outbound-request',
             ],

             'Sales Team' => [
                 'purchases sidebar',
                 'customers sidebar',
                 'sales sidebar',
                 'outbound-request sidebar',
                 'products sidebar',
                 'warehouses sidebar',
                 'inbound-request sidebar',

                 'show-only warehouse',
                 'show-only inventory',
                 'crud inventory',
                 'crud customer',
                 'crud customer-complaint',
                 'crud sales',
                 'show-only product',
                 'show-only location',
                 'show-only customer-complaint',
                 'show-only outbound-request',
             ],

             'HR Manager' => [
                'employees sidebar',
                'role-access sidebar',

                'team-only role-access',
                'crud employee',
                'show-only customer-complaint',
            ],

            'HR Team' => [
                'employees sidebar',

                'crud employee',
                'show-only customer-complaint',
            ],
            ];

        // Create roles and assign permissions
        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }
    }
}
