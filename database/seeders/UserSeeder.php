<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        User::factory()->create([
            'name' => 'Site Admin',
            'email' => 'admin@merxify.app',
            'group' => 'admin',
        ]);

        // Customer User
        User::factory()->create([
            'name' => 'Site Customer',
            'email' => 'customer@merxify.app',
        ]);
    }
}
