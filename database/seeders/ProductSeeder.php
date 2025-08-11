<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name' => 'Éternelle Rose',
                'slug' => 'eternelle-rose',
                'description' => 'Un parfum d\'exception qui capture l\'essence intemporelle de la rose française.',
                'short_description' => 'L\'essence intemporelle de la rose française',
                'price' => 89.00,
                'product_type' => 'parfum',
                'category' => 'femme',
                'type' => 'Eau de Parfum',
                'size' => '50ml',
                'images' => json_encode([
                    'https://images.unsplash.com/photo-1541961017774-22349e4a1262?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1594035910387-fea47794261f?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'
                ]),
                'notes' => json_encode([
                    'head' => ['Rose de Grasse', 'Bergamote', 'Cassis'],
                    'heart' => ['Pivoine', 'Jasmin', 'Muguet'],
                    'base' => ['Bois de Santal', 'Musc Blanc', 'Ambre']
                ]),
                'stock' => 25,
                'is_active' => true,
                'is_featured' => true,
                'badge' => 'Signature'
            ],
            [
                'name' => 'Ambre Mystérieux',
                'slug' => 'ambre-mysterieux',
                'description' => 'Une composition envoûtante où l\'ambre révèle toute sa splendeur.',
                'short_description' => 'L\'éclat mystérieux de l\'ambre oriental',
                'price' => 95.00,
                'product_type' => 'parfum',
                'category' => 'homme',
                'type' => 'Eau de Parfum',
                'size' => '75ml',
                'images' => json_encode([
                    'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'
                ]),
                'notes' => json_encode([
                    'head' => ['Cardamome', 'Poivre Rose', 'Citron Vert'],
                    'heart' => ['Ambre Gris', 'Encens', 'Rose Bulgare'],
                    'base' => ['Oud', 'Cèdre', 'Patchouli']
                ]),
                'stock' => 18,
                'is_active' => true,
                'is_featured' => true,
                'badge' => 'Exclusif'
            ],
            [
                'name' => 'Sérum Éclat Précieux',
                'slug' => 'serum-eclat-precieux',
                'description' => 'Un sérum révolutionnaire aux actifs de luxe pour une peau éclatante et régénérée. Formulé avec de l\'or 24 carats et des extraits de rose de Damas.',
                'short_description' => 'Sérum anti-âge aux actifs précieux',
                'price' => 125.00,
                'product_type' => 'cosmetique',
                'category' => 'soins_visage',
                'type' => 'Sérum',
                'size' => '30ml',
                'brand' => 'Héritaj Beauté',
                'images' => json_encode([
                    'https://images.unsplash.com/photo-1571875257727-256c39da42af?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1556228720-195a672e8a03?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'
                ]),
                'notes' => json_encode([
                    'actifs' => ['Or 24 carats', 'Acide Hyaluronique', 'Extrait de Rose de Damas'],
                    'benefices' => ['Anti-âge', 'Hydratation intense', 'Éclat immédiat'],
                    'utilisation' => ['Matin et soir', 'Sur peau propre', 'Avant la crème']
                ]),
                'stock' => 15,
                'is_active' => true,
                'is_featured' => true,
                'badge' => 'Innovation'
            ]
        ];

        foreach ($products as $productData) {
            Product::updateOrCreate(
                ['slug' => $productData['slug']],
                $productData
            );
        }
    }
}