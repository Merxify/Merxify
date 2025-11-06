<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::factory()->admin()->create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@merxify.app',
        ]);

        // Create Customer users
        $customers = User::factory()->customer()->count(50)->create();

        // Create addresses for users
        $allUsers = collect([$admin])->merge($customers);

        $allUsers->each(function ($user) {
            // Each user gets 1-3 addresses
            $addresses = Address::factory()->count(3)->create([
                'user_id' => $user->id,
            ]);

            // Make first address default
            $addresses->first()?->update(['is_default' => true]);
        });
    }
}
