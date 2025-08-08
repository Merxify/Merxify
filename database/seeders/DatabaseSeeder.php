<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionsSeeder::class,
        ]);

        // Create Demo Users
        $customer = User::factory()->create([
            'name' => 'Customer',
            'email' => 'customer@example.com',
        ]);

        $customerRole = Role::where('name', 'customer')->first();
        $customer->assignRole($customerRole);

        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);

        $adminRole = Role::where('name', 'admin')->first();
        $admin->assignRole($adminRole);

        $superAdmin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'super-admin@example.com',
        ]);

        $superAdminRole = Role::where('name', 'Super-Admin')->first();
        $superAdmin->assignRole($superAdminRole);
    }
}
