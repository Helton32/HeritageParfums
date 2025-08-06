<?php

namespace App\Services;

class OlfactoryNotesService
{
    public static function getNoteData($note)
    {
        $normalizedNote = strtolower(trim($note));

        $noteMap = [
            // NOTES DE TÊTE - Fraîches et vives
            'bergamote' => [
                'image' => 'https://images.unsplash.com/photo-1557800636-894a64c1696f?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                'category' => 'head',
                'type' => 'agrume',
                'color' => '#FFE135'
            ],
            'citron' => [
                'image' => 'https://images.unsplash.com/photo-1568702846914-96b305d2aaeb?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                'category' => 'head',
                'type' => 'agrume',
                'color' => '#FFEB3B'
            ],
            'orange' => [
                'image' => 'https://images.unsplash.com/photo-1557800634-7bf3c7e2d6b6?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                'category' => 'head',
                'type' => 'agrume',
                'color' => '#FF9800'
            ],
            'pamplemousse' => [
                'image' => 'https://images.unsplash.com/photo-1570797197190-8e003a00c846?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                'category' => 'head',
                'type' => 'agrume',
                'color' => '#FFCDD2'
            ],
            'menthe' => [
                'image' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                'category' => 'head',
                'type' => 'herbe',
                'color' => '#4CAF50'
            ],
            'basilic' => [
                'image' => 'https://images.unsplash.com/photo-1618450237519-5f8147ba9eb0?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                'category' => 'head',
                'type' => 'herbe',
                'color' => '#66BB6A'
            ],
            'lavande' => [
                'image' => 'https://images.unsplash.com/photo-1499002238440-d264edd596ec?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                'category' => 'head',
                'type' => 'fleur',
                'color' => '#9C27B0'
            ],
            'orchidée' => [
                'image' => 'https://images.unsplash.com/photo-1509587584298-0f3b3a3a1797?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                'category' => 'head',
                'type' => 'fleur',
                'color' => '#E1BEE7'
            ],
            'mandarine' => [
                'image' => 'https://images.unsplash.com/photo-1611080626919-7cf5a9dbab5b?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                'category' => 'head',
                'type' => 'agrume',
                'color' => '#FF9800'
            ],
            'lime' => [
                'image' => 'https://images.unsplash.com/photo-1541004526-581c4ba048c0?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                'category' => 'head',
                'type' => 'agrume',
                'color' => '#8BC34A'
            ],
            'thé vert' => [
                'image' => 'https://images.unsplash.com/photo-1594631661960-39a2ede0ca38?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                'category' => 'head',
                'type' => 'herbe',
                'color' => '#4CAF50'
            ],
            'eucalyptus' => [
                'image' => 'https://images.unsplash.com/photo-1615114662224-8fcbcac0cff7?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                'category' => 'head',
                'type' => 'herbe',
                'color' => '#81C784'
            ],
            'romarin' => [
                'image' => 'https://images.unsplash.com/photo-1615114468445-c4e5a3bc99ac?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                'category' => 'head',
                'type' => 'herbe',
                'color' => '#689F38'
            ],
            'gingembre' => [
                'image' => 'https://images.unsplash.com/photo-1599807098381-4b0b9d18ad6b?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                'category' => 'head',
                'type' => 'épice',
                'color' => '#FF8A65'
            ],

            // NOTES DE CŒUR
            'rose' => [
                'image' => 'https://images.unsplash.com/photo-1518895949257-7621c3c786d7?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                'category' => 'heart',
                'type' => 'fleur',
                'color' => '#E91E63'
            ],
            'jasmin' => [
                'image' => 'https://images.unsplash.com/photo-1592650328120-53847127c48c?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                'category' => 'heart',
                'type' => 'fleur',
                'color' => '#FFFFFF'
            ],
            'pivoine' => [
                'image' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                'category' => 'heart',
                'type' => 'fleur',
                'color' => '#F8BBD9'
            ],
            'ylang-ylang' => [
                'image' => 'https://images.unsplash.com/photo-1594736797933-d0b22d5f5b85?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                'category' => 'heart',
                'type' => 'fleur',
                'color' => '#FFF9C4'
            ],
            'géranium' => [
                'image' => 'https://images.unsplash.com/photo-1563207153-f403bf289096?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                'category' => 'heart',
                'type' => 'fleur',
                'color' => '#E1BEE7'
            ],
            'iris' => [
                'image' => 'https://images.unsplash.com/photo-1553504472-53e694ad3abe?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                'category' => 'heart',
                'type' => 'fleur',
                'color' => '#7986CB'
            ],
            'muguet' => [
                'image' => 'https://images.unsplash.com/photo-1557804506-669a67965ba0?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                'category' => 'heart',
                'type' => 'fleur',
                'color' => '#F1F8E9'
            ],
            'freesia' => [
                'image' => 'https://images.unsplash.com/photo-1583327940343-e2b6ee0e7250?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                'category' => 'heart',
                'type' => 'fleur',
                'color' => '#E8F5E8'
            ],
            'lys' => [
                'image' => 'https://images.unsplash.com/photo-1594736797933-d0b22d5f5b85?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                'category' => 'heart',
                'type' => 'fleur',
                'color' => '#FFFFFF'
            ],
            'gardénia' => [
                'image' => 'https://images.unsplash.com/photo-1519904981063-b0cf448d479e?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                'category' => 'heart',
                'type' => 'fleur',
                'color' => '#FFFDE7'
            ],
            'magnolia' => [
                'image' => 'https://images.unsplash.com/photo-1520637836862-4d197d17c0a4?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                'category' => 'heart',
                'type' => 'fleur',
                'color' => '#F8BBD9'
            ],
            'tubéreuse' => [
                'image' => 'https://images.unsplash.com/photo-1595055001241-4de44728c3e8?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                'category' => 'heart',
                'type' => 'fleur',
                'color' => '#FFFFFF'
            ],
            'cardamome' => [
                'image' => 'https://images.unsplash.com/photo-1598640443855-4eae8e5b2e6a?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                'category' => 'heart',
                'type' => 'épice',
                'color' => '#C8E6C9'
            ],
            'cannelle' => [
                'image' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                'category' => 'heart',
                'type' => 'épice',
                'color' => '#8D6E63'
            ],
            'poivre noir' => [
                'image' => 'https://images.unsplash.com/photo-1612461084632-27b89d6235bb?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                'category' => 'heart',
                'type' => 'épice',
                'color' => '#424242'
            ],
            // ... tu peux continuer à ajouter les autres éléments ici
        ];

        if (!isset($noteMap[$normalizedNote])) {
            foreach ($noteMap as $key => $data) {
                if (strpos($normalizedNote, $key) !== false || strpos($key, $normalizedNote) !== false) {
                    return array_merge($data, ['name' => $note]);
                }
            }

            return [
                'image' => 'https://images.unsplash.com/photo-1574594734869-7b5e4badd6e5?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80',
                'category' => 'heart',
                'type' => 'autre',
                'color' => '#9E9E9E',
                'name' => $note
            ];
        }

        return array_merge($noteMap[$normalizedNote], ['name' => $note]);
    }

    public static function getProductNotes($product)
    {
        $notes = $product->notes;
        $structuredNotes = [
            'head' => [],
            'heart' => [],
            'base' => []
        ];

        if (is_array($notes)) {
            if (isset($notes['head']) || isset($notes['heart']) || isset($notes['base'])) {
                foreach (['head', 'heart', 'base'] as $category) {
                    if (isset($notes[$category]) && is_array($notes[$category])) {
                        foreach ($notes[$category] as $note) {
                            if (is_string($note)) {
                                $noteData = self::getNoteData($note);
                                $structuredNotes[$category][] = $noteData;
                            }
                        }
                    }
                }
            } else {
                foreach ($notes as $note) {
                    if (is_string($note)) {
                        $noteData = self::getNoteData($note);
                        $structuredNotes[$noteData['category']][] = $noteData;
                    }
                }
            }
        }

        return $structuredNotes;
    }

    public static function getNoteCategoryLabels()
    {
        return [
            'head' => 'Notes de Tête',
            'heart' => 'Notes de Cœur',
            'base' => 'Notes de Fond'
        ];
    }

    public static function getCategoryColor($category)
    {
        $colors = [
            'head' => '#E3F2FD',
            'heart' => '#FCE4EC',
            'base' => '#FFF3E0'
        ];

        return $colors[$category] ?? '#F5F5F5';
    }
}
