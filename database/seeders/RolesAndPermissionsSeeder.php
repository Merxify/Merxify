<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions
        $permissions = [
            'manage users',
            'manage roles',
            'manage products',
            'manage categories',
            'manage orders',
            'manage payments',
            'manage customers',
            'manage webhooks',
            'manage settings',
            'view reports',
            'place orders',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $staff = Role::firstOrCreate(['name' => 'staff']);
        $customer = Role::firstOrCreate(['name' => 'customer']);

        // Assign Permissions
        $admin->givePermissionTo(Permission::all());

        $staff->givePermissionTo([
            'manage products',
            'manage categories',
            'manage orders',
            'manage customers',
            'view reports',
        ]);

        $customer->givePermissionTo([
            'place orders',
        ]);
    }
}
