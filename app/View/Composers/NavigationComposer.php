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
        // Récupérer les produits actifs groupés par type et catégorie
        $parfums = Product::where('is_active', true)
                         ->where('product_type', 'parfum')
                         ->orderBy('is_featured', 'desc')
                         ->orderBy('created_at', 'desc')
                         ->limit(12)
                         ->get()
                         ->groupBy('category');

        $cosmetiques = Product::where('is_active', true)
                             ->where('product_type', 'cosmetique')
                             ->orderBy('is_featured', 'desc')
                             ->orderBy('created_at', 'desc')
                             ->limit(12)
                             ->get()
                             ->groupBy('category');

        // Produit en vedette pour la section "À la une"
        $featuredProduct = Product::where('is_active', true)
                                 ->where('is_featured', true)
                                 ->orderBy('created_at', 'desc')
                                 ->first();

        // Produit cosmétique en vedette spécifiquement
        $featuredCosmetique = Product::where('is_active', true)
                                   ->where('product_type', 'cosmetique')
                                   ->where('is_featured', true)
                                   ->orderBy('created_at', 'desc')
                                   ->first();

        // Si aucun cosmétique featured, prendre le premier cosmétique actif
        if (!$featuredCosmetique) {
            $featuredCosmetique = Product::where('is_active', true)
                                       ->where('product_type', 'cosmetique')
                                       ->orderBy('created_at', 'desc')
                                       ->first();
        }

        // Catégories pour les filtres
        $parfumCategories = [
            'niche' => 'Parfums de Niche',
            'exclusifs' => 'Collections Exclusives',
            'nouveautes' => 'Nouveautés'
        ];

        $cosmetiqueCategories = [
            'soins_visage' => 'Soins du Visage',
            'soins_corps' => 'Soins du Corps',
            'nouveautes_cosmetiques' => 'Nouveautés Cosmétiques'
        ];

        $view->with([
            'navbarParfums' => $parfums,
            'navbarCosmetiques' => $cosmetiques,
            'navbarFeaturedProduct' => $featuredProduct,
            'navbarFeaturedCosmetique' => $featuredCosmetique,
            'parfumCategories' => $parfumCategories,
            'cosmetiqueCategories' => $cosmetiqueCategories,
        ]);
    }
}
