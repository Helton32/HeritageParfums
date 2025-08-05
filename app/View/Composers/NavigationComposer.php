<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\Product;

class NavigationComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        // Récupérer les catégories et produits pour le mega-menu
        $categories = [
            'femme' => 'Parfums Femme',
            'homme' => 'Parfums Homme',
            'exclusifs' => 'Collections Exclusives',
            'nouveautes' => 'Nouveautés'
        ];
        
        $productsByCategory = [];
        foreach (array_keys($categories) as $categoryKey) {
            $productsByCategory[$categoryKey] = Product::where('category', $categoryKey)
                                                      ->where('is_active', true)
                                                      ->take(5)
                                                      ->get();
        }

        // Produit en vedette pour la section "À la une"
        $featuredProducts = Product::where('is_featured', true)
                                  ->where('is_active', true)
                                  ->take(1)
                                  ->get();

        $view->with(compact('categories', 'productsByCategory', 'featuredProducts'));
    }
}
