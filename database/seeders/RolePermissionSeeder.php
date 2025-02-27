<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define permissions
        $permissions = [
            'company sidebar',
            'user sidebar',
            'roles sidebar',
            'permissions sidebar',
            
            'crud roles',
            
            'crud user',
            
            'companies',
            'crud company',

            'crud permissions',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Define roles and their permissions
        $roles = [
            'Guest' => [

            ],
            'User' => [
                'company sidebar',

                'companies',
                'crud company',
            ],
            'Admin' => [
                'company sidebar',
                'user sidebar',
                'roles sidebar',
                'permissions sidebar',
                
                'crud roles',

                'crud user',

                'companies',
                'crud company',

                'crud permissions',
            ],
        ];

        // Create roles and assign permissions
        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }
    }
}
