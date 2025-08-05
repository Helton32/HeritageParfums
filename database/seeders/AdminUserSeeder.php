<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'marouanehirch@admin.com'],
            [
                'name' => 'marouanehirch',
                'email' => 'marouanehirch@admin.com',
                'password' => Hash::make('test123'),
                'email_verified_at' => now(),
            ]
        );
    }
}
