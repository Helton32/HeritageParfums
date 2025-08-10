#!/bin/bash

echo "🔧 Diagnostic Apple Pay & Checkout - HeritageParfums"
echo "=================================================="
echo ""

# Test 1: Vérifier la syntaxe PHP
echo "1️⃣ Test syntaxe PaymentController..."
php -l app/Http/Controllers/PaymentController.php
if [ $? -eq 0 ]; then
    echo "   ✅ Syntaxe PaymentController OK"
else
    echo "   ❌ Erreur de syntaxe PaymentController"
    exit 1
fi

# Test 2: Vérifier les routes
echo ""
echo "2️⃣ Test routes de paiement..."
php artisan route:list | grep payment | head -6

# Test 3: Tester la méthode checkout avec un panier vide
echo ""
echo "3️⃣ Test méthode checkout..."
php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';
\$kernel = \$app->make(Illuminate\Contracts\Console\Kernel::class);
\$kernel->bootstrap();

use App\Http\Controllers\CartController;

try {
    \$cartController = new CartController();
    \$cartData = \$cartController->getCartData();
    echo '   ✅ getCartData() fonctionne\n';
    echo '   📊 Items: ' . count(\$cartData['items']) . '\n';
    echo '   💰 Total: ' . \$cartData['total'] . '€\n';
} catch (Exception \$e) {
    echo '   ❌ Erreur getCartData: ' . \$e->getMessage() . '\n';
    exit(1);
}
"

# Test 4: Vérifier qu'un produit a un prix valide
echo ""
echo "4️⃣ Test prix produit..."
php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';
\$kernel = \$app->make(Illuminate\Contracts\Console\Kernel::class);
\$kernel->bootstrap();

try {
    \$product = \App\Models\Product::where('is_active', true)->first();
    if (\$product) {
        \$price = \$product->getCurrentPrice();
        echo '   ✅ Produit: ' . \$product->name . '\n';
        echo '   💰 Prix: ' . (\$price ?? 'NULL') . '€\n';
        
        if (\$price === null) {
            echo '   ⚠️  PROBLÈME: getCurrentPrice() retourne NULL\n';
            echo '   📊 Prix de base: ' . \$product->price . '€\n';
            echo '   🏷️  En promotion: ' . (\$product->is_on_promotion ? 'Oui' : 'Non') . '\n';
        }
    } else {
        echo '   ⚠️  Aucun produit actif trouvé\n';
    }
} catch (Exception \$e) {
    echo '   ❌ Erreur produit: ' . \$e->getMessage() . '\n';
}
"

# Test 5: Simuler un appel à checkout
echo ""
echo "5️⃣ Test simulation checkout..."
curl -s -o /dev/null -w \"HTTP Status: %{http_code}\" \"http://localhost:8000/payment/checkout\" 2>/dev/null
echo ""

# Test 6: Vérifier les dernières erreurs dans les logs
echo ""
echo "6️⃣ Dernières erreurs dans les logs..."
if [ -f "storage/logs/laravel.log" ]; then
    echo "   🔍 Dernières erreurs:"
    grep -i error storage/logs/laravel.log | tail -3 | while read line; do
        echo "   ❌ $line"
    done
else
    echo "   ⚠️  Fichier de log non trouvé"
fi

echo ""
echo "📋 DIAGNOSTIC TERMINÉ"
echo "===================="
echo ""
echo "Si vous voyez des erreurs ci-dessus, voici les prochaines étapes :"
echo "1. 🔧 Corriger les erreurs identifiées"
echo "2. 🧪 Tester directement : /payment/checkout"  
echo "3. 🧪 Tester Apple Pay : /payment/create-apple-pay-session"
echo "4. 📊 Consulter les logs : tail -f storage/logs/laravel.log"
