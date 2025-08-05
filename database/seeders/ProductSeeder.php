<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Produit principal uniquement - Éternelle Rose
        Product::create([
            'name' => 'Éternelle Rose',
            'slug' => 'eternelle-rose',
            'description' => 'Un parfum floral sophistiqué qui capture l\'essence de la rose éternelle. Notes de tête : bergamote, cassis. Notes de cœur : rose de Damas, pivoine. Notes de fond : musc blanc, bois de santal.',
            'short_description' => 'Un parfum floral sophistiqué à la rose éternelle.',
            'price' => 185.00,
            'category' => 'femme',
            'type' => 'Eau de Parfum',
            'size' => '100ml',
            'images' => ['https://images.unsplash.com/photo-1541961017774-22349e4a1262?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'],
            'notes' => [
                'head' => ['bergamote', 'cassis'],
                'heart' => ['rose de Damas', 'pivoine'],
                'base' => ['musc blanc', 'bois de santal']
            ],
            'stock' => 25,
            'is_active' => true,
            'is_featured' => true,
            'badge' => '',
        ]);
    }
}
