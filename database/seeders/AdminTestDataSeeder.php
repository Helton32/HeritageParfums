<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contact;
use App\Models\Product;

class AdminTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer des messages de contact de test
        Contact::create([
            'name' => 'Marie Dubois',
            'email' => 'marie.dubois@email.com',
            'subject' => 'Conseil parfum pour cadeau',
            'message' => 'Bonjour, je cherche un parfum pour offrir à ma mère pour son anniversaire. Elle aime les senteurs florales et délicates. Auriez-vous des recommandations ?',
            'phone' => '01 23 45 67 89',
        ]);

        Contact::create([
            'name' => 'Pierre Martin',
            'email' => 'p.martin@company.fr',
            'subject' => 'Question sur une commande',
            'message' => 'Bonjour, j\'ai passé commande il y a 3 jours (commande #HP1234) et je n\'ai pas encore reçu de confirmation d\'expédition. Pourriez-vous me donner des nouvelles ?',
            'phone' => '06 78 90 12 34',
            'is_read' => true,
        ]);

        Contact::create([
            'name' => 'Sophie Laurent',
            'email' => 'sophie.laurent@gmail.com',
            'subject' => 'Demande d\'information',
            'message' => 'Bonsoir, je suis très intéressée par le parfum Éternelle Rose. Pourriez-vous me dire s\'il est possible de le tester en boutique avant l\'achat ? Merci beaucoup.',
            'is_read' => true,
            'replied_at' => now()->subDays(1),
            'admin_notes' => 'Client informée que les tests sont disponibles en boutique du lundi au samedi.',
        ]);

        Contact::create([
            'name' => 'Lucas Petit',
            'email' => 'lucas.petit@exemple.com',
            'subject' => 'Service client',
            'message' => 'Je souhaiterais échanger un parfum acheté la semaine dernière car il ne correspond pas à mes attentes. Quelle est la procédure ?',
        ]);

        // Créer des produits de test (si pas déjà existants)
        if (Product::where('slug', 'mystique-noir')->doesntExist()) {
            Product::create([
                'name' => 'Mystique Noir',
                'slug' => 'mystique-noir',
                'description' => 'Un parfum mystérieux aux notes de jasmin noir, de bois de santal et d\'ambre. Une fragrance envoûtante qui révèle la part de mystère qui sommeille en chaque femme. Cette composition sophistiquée s\'ouvre sur des notes de tête pétillantes de bergamote et cassis, révélant un cœur opulent de jasmin noir et rose de Damas, pour se terminer sur un fond sensuel d\'ambre, patchouli et bois de santal.',
                'short_description' => 'Parfum mystérieux aux notes de jasmin noir et d\'ambre',
                'price' => 89.90,
                'category' => 'femme',
                'type' => 'eau_de_parfum',
                'size' => '50ml',
                'stock' => 25,
                'is_active' => true,
                'is_featured' => false,
                'badge' => 'Nouveauté',
                'notes' => ['Bergamote', 'Cassis', 'Jasmin noir', 'Rose de Damas', 'Ambre', 'Patchouli', 'Bois de santal'],
                'images' => [
                    'https://images.unsplash.com/photo-1541961017774-22349e4a1262?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1563170351-be82bc888aa4?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'
                ]
            ]);
        }

        if (Product::where('slug', 'gentleman-elegance')->doesntExist()) {
            Product::create([
                'name' => 'Gentleman Élégance',
                'slug' => 'gentleman-elegance',
                'description' => 'Un parfum sophistiqué pour l\'homme moderne. Notes de bergamote fraîche, cœur de lavande française et fond de cèdre et patchouli. L\'essence même de l\'élégance masculine. Cette fragrance raconte l\'histoire d\'un homme confiant, élégant et charismatique. Elle s\'ouvre sur des agrumes éclatants, se développe avec des aromates nobles et se termine sur des bois précieux.',
                'short_description' => 'Parfum sophistiqué aux notes de bergamote et lavande',
                'price' => 95.00,
                'category' => 'homme',
                'type' => 'eau_de_toilette',
                'size' => '100ml',
                'stock' => 18,
                'is_active' => true,
                'is_featured' => true,
                'notes' => ['Bergamote', 'Citron vert', 'Lavande française', 'Romarin', 'Cèdre', 'Patchouli', 'Musc blanc'],
                'images' => [
                    'https://images.unsplash.com/photo-1563170351-be82bc888aa4?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'
                ]
            ]);
        }

        if (Product::where('slug', 'soleil-dor')->doesntExist()) {
            Product::create([
                'name' => 'Soleil d\'Or',
                'slug' => 'soleil-dor',
                'description' => 'Une eau fraîche estivale qui capture l\'essence du soleil méditerranéen. Notes d\'agrumes pétillants, de fleur d\'oranger et de sable chaud. Perfect pour les journées ensoleillées et les soirées d\'été.',
                'short_description' => 'Eau fraîche aux agrumes et fleur d\'oranger',
                'price' => 65.00,
                'category' => 'nouveautes',
                'type' => 'eau_fraiche',
                'size' => '75ml',
                'stock' => 0,
                'is_active' => true,
                'is_featured' => false,
                'badge' => 'Édition Limitée',
                'notes' => ['Citron', 'Orange douce', 'Fleur d\'oranger', 'Lavande', 'Sable blanc', 'Ambre solaire'],
                'images' => [
                    'https://images.unsplash.com/photo-1588405748880-12d1d2a59db9?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'
                ]
            ]);
        }

        if (Product::where('slug', 'nuit-de-velours')->doesntExist()) {
            Product::create([
                'name' => 'Nuit de Velours',
                'slug' => 'nuit-de-velours',
                'description' => 'Un parfum de luxe aux facettes multiples. Cette création exclusive marie la sophistication de l\'iris et de la violette à la sensualité du musc et de la vanille. Un parfum d\'exception pour les moments privilégiés.',
                'short_description' => 'Parfum de luxe aux notes d\'iris et vanille',
                'price' => 150.00,
                'category' => 'exclusifs',
                'type' => 'parfum',
                'size' => '30ml',
                'stock' => 8,
                'is_active' => true,
                'is_featured' => true,
                'badge' => 'Exclusif',
                'notes' => ['Iris', 'Violette', 'Freesia', 'Musc blanc', 'Vanille Madagascar', 'Bois de cashmere'],
                'images' => [
                    'https://images.unsplash.com/photo-1594736797933-d0c62c5b8092?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
                    'https://images.unsplash.com/photo-1585386959984-a4155224a1ad?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'
                ]
            ]);
        }
    }
}
