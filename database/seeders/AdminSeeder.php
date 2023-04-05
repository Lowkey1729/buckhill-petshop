<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->create([
            'first_name' => 'admin',
            'last_name' => 'admin',
            'email' => 'admin@buckhill.co.uk',
            'password' => bcrypt('admin'),
            'email_verified_at' => now(),
            'avatar' => fake()->uuid,
            'is_marketing' => 0,
            'is_admin' => 1,
            'phone_number' => fake()->phoneNumber(),
            'address' => fake()->address(),
        ]);
    }
}
