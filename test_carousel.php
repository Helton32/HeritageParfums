<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

echo "=== TEST D'AFFICHAGE DU CARROUSEL ===\n\n";

// Simuler la logique du carrousel (même que dans web.php)
$featuredProducts = Product::where('is_featured', true)
                          ->where('is_active', true)
                          ->orderBy('created_at', 'desc')
                          ->get();

$activeProducts = Product::where('is_active', true)
                        ->where('is_featured', false)
                        ->orderBy('created_at', 'desc')
                        ->get();

$allActiveProducts = $featuredProducts->concat($activeProducts)->take(6);

echo "🎯 ANALYSE DE L'AFFICHAGE CARROUSEL\n\n";
echo "Produits en vedette: " . $featuredProducts->count() . "\n";
echo "Produits actifs (non vedette): " . $activeProducts->count() . "\n";
echo "Total affiché dans le carrousel: " . $allActiveProducts->count() . "/6\n\n";

echo "📋 LISTE DES PRODUITS DU CARROUSEL :\n";

foreach ($allActiveProducts as $index => $product) {
    $position = $index + 1;
    $status = $product->is_featured ? 'EN VEDETTE' : 'ACTIF';
    
    echo "--- Position {$position} ---\n";
    echo "✅ Nom: '{$product->name}'\n";
    echo "✅ Statut: {$status}\n";
    
    // Vérifier les éléments critiques pour l'affichage
    $issues = [];
    
    if (empty($product->name)) {
        $issues[] = "❌ Nom manquant";
    }
    
    if (empty($product->short_description)) {
        $issues[] = "❌ Description courte manquante";
    }
    
    if (empty($product->main_image) || $product->main_image === 'https://images.unsplash.com/photo-1541961017774-22349e4a1262?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80') {
        $issues[] = "⚠️ Image par défaut utilisée";
    }
    
    if (!$product->is_active) {
        $issues[] = "❌ Produit inactif";
    }
    
    if (empty($issues)) {
        echo "✅ Affichage: PARFAIT\n";
    } else {
        echo "⚠️ Problèmes: " . implode(", ", $issues) . "\n";
    }
    
    echo "   Description: " . ($product->short_description ?? 'MANQUANTE') . "\n";
    echo "   Image: " . (strlen($product->main_image) > 50 ? substr($product->main_image, 0, 47) . '...' : $product->main_image) . "\n";
    echo "\n";
}

echo "🔧 RECOMMANDATIONS :\n\n";

// Produits avec problèmes
$problematicProducts = Product::where('is_active', true)
    ->where(function($query) {
        $query->whereNull('short_description')
              ->orWhere('short_description', '')
              ->orWhereNull('images')
              ->orWhere('images', '[]')
              ->orWhere('images', '[""]');
    })
    ->get();

if ($problematicProducts->count() > 0) {
    echo "⚠️ Produits actifs avec problèmes d'affichage :\n";
    foreach ($problematicProducts as $product) {
        echo "- {$product->name} (ID: {$product->id})\n";
        if (empty($product->short_description)) {
            echo "  • Description courte manquante\n";
        }
        if (empty($product->images) || $product->images === [''] || $product->images === []) {
            echo "  • Image manquante\n";
        }
    }
} else {
    echo "✅ Tous les produits actifs sont prêts pour l'affichage!\n";
}

echo "\n💡 CONSEILS POUR CRÉER DE NOUVEAUX PRODUITS :\n";
echo "1. ✅ Cocher 'Produit actif' pour l'affichage sur l'accueil\n";
echo "2. ✅ Cocher 'Produit en vedette' pour l'affichage prioritaire\n";
echo "3. ✅ Remplir OBLIGATOIREMENT la description courte\n";
echo "4. ✅ Ajouter OBLIGATOIREMENT au moins une image\n";
echo "5. ✅ Tester l'affichage après création\n";

echo "\n=== FIN DU TEST ===\n";
