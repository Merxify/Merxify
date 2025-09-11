<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        $admin = User::firstOrCreate([
            'name' => 'Admin User',
            'email' => 'admin@merxify.test',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        // Staff User
        $staff = User::firstOrCreate([
            'name' => 'Staff User',
            'email' => 'staff@merxify.test',
            'password' => Hash::make('password'),
        ]);
        $staff->assignRole('staff');

        // Customer User
        $customer = User::firstOrCreate([
            'name' => 'Customer User',
            'email' => 'customer@merxify.test',
            'password' => Hash::make('password'),
        ]);
        $customer->assignRole('customer');
    }
}
