<?php

namespace Database\Seeders;

use App\Models\Dorm;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run() : void
    {
        // Admin
        User::factory()->create([
            'name' => 'Danar Wijanarko',
            'phone' => '08232324252',
            'email' => 'danarwijanarko98@gmail.com',
            'gender' => 'male',
            'password' => Hash::make('123456'),
            'role' => 'admin',
        ]);
        // owner
        User::factory()->create([
            'name' => 'Owner',
            'phone' => '082322424242',
            'email' => 'owner@gmail.com',
            'gender' => 'female',
            'password' => Hash::make('123456'),
            'role' => 'owner',
        ]);
        // Client
        User::factory()->create([
            'name' => 'Client',
            'phone' => '082322424242',
            'email' => 'client@gmail.com',
            'gender' => 'female',
            'password' => Hash::make('123456'),
            'role' => 'client',
        ]);

        User::factory(3)->create();

        // Dorm::factory(50)->create();
    }
}
