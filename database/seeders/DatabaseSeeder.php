<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed produit principal pour Heritage Parfums
        $this->call([
            ProductSeeder::class,
            AdminUserSeeder::class,
            ShippingCarrierSeeder::class,
        ]);
    }
}
