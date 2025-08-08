<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Products Permissions
        $productsPermissions = [
            'products:viewAny',
            'products:view',
            'products:create',
            'products:update',
            'products:delete',
        ];

        foreach ($productsPermissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create Categories Permissions
        $categoriesPermissions = [
            'categories:viewAny',
            'categories:view',
            'categories:create',
            'categories:update',
            'categories:delete',
        ];

        foreach ($categoriesPermissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign existing permissions
        $customer = Role::create(['name' => 'customer']);

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo($productsPermissions);
        $admin->givePermissionTo($categoriesPermissions);

        $superAdmin = Role::create(['name' => 'Super-Admin']);
    }
}
