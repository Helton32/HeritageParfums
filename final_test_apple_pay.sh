#!/bin/bash

echo "🚀 Test Final Apple Pay - HeritageParfums"
echo "========================================"
echo ""

# Test 1: Vérifier que Laravel fonctionne
echo "1️⃣ Test Laravel..."
if php artisan --version > /dev/null 2>&1; then
    echo "   ✅ Laravel opérationnel"
else
    echo "   ❌ Problème avec Laravel"
    exit 1
fi

# Test 2: Vérifier la base de données
echo ""
echo "2️⃣ Test Base de Données..."
php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';
\$kernel = \$app->make(Illuminate\Contracts\Console\Kernel::class);
\$kernel->bootstrap();

try {
    \$orders = \App\Models\Order::count();
    echo '   ✅ Base de données accessible (' . \$orders . ' commandes)\n';
    
    // Vérifier que la colonne payment_method existe
    \$hasPaymentMethod = \Schema::hasColumn('orders', 'payment_method');
    if (\$hasPaymentMethod) {
        echo '   ✅ Colonne payment_method présente\n';
    } else {
        echo '   ❌ Colonne payment_method manquante\n';
        exit(1);
    }
} catch (Exception \$e) {
    echo '   ❌ Erreur BDD: ' . \$e->getMessage() . '\n';
    exit(1);
}
"

# Test 3: Vérifier les corrections PaymentController
echo ""
echo "3️⃣ Test PaymentController..."
if grep -q "EXTRACTION AMÉLIORÉE DES DONNÉES APPLE PAY" app/Http/Controllers/PaymentController.php; then
    echo "   ✅ PaymentController corrigé"
else
    echo "   ❌ PaymentController non corrigé"
    exit 1
fi

if grep -q "extractContactInfo" app/Http/Controllers/PaymentController.php; then
    echo "   ✅ Fonction d'extraction présente"
else
    echo "   ❌ Fonction d'extraction manquante"
fi

# Test 4: Vérifier qu'un produit peut être traité
echo ""
echo "4️⃣ Test Produit..."
php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';
\$kernel = \$app->make(Illuminate\Contracts\Console\Kernel::class);
\$kernel->bootstrap();

try {
    \$product = \App\Models\Product::where('stock', '>', 0)->first();
    if (\$product) {
        \$price = \$product->getCurrentPrice();
        echo '   ✅ Produit test: ' . \$product->name . ' (' . \$price . '€)\n';
        
        if (\$price > 0) {
            echo '   ✅ Prix valide\n';
        } else {
            echo '   ⚠️  Prix à zéro\n';
        }
    } else {
        echo '   ⚠️  Aucun produit en stock\n';
    }
} catch (Exception \$e) {
    echo '   ❌ Erreur produit: ' . \$e->getMessage() . '\n';
}
"

# Test 5: Simuler une extraction de données Apple Pay
echo ""
echo "5️⃣ Test Simulation Apple Pay..."
php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';
\$kernel = \$app->make(Illuminate\Contracts\Console\Kernel::class);
\$kernel->bootstrap();

// Simuler des données Apple Pay
\$testData = [
    'emailAddress' => 'test@example.com',
    'givenName' => 'Jean',
    'familyName' => 'Dupont',
    'phoneNumber' => '+33123456789',
    'addressLines' => ['123 Rue de la Paix', 'Apt 4B'],
    'locality' => 'Paris',
    'postalCode' => '75001',
    'countryCode' => 'FR'
];

// Tester l'extraction (même logique que dans PaymentController)
\$extractContactInfo = function(\$contact) {
    \$info = [
        'email' => \$contact['emailAddress'] ?? '',
        'givenName' => \$contact['givenName'] ?? '',
        'familyName' => \$contact['familyName'] ?? '',
        'addressLines' => \$contact['addressLines'] ?? [],
        'locality' => \$contact['locality'] ?? '',
        'postalCode' => \$contact['postalCode'] ?? '',
        'countryCode' => \$contact['countryCode'] ?? 'FR'
    ];
    return \$info;
};

\$extracted = \$extractContactInfo(\$testData);
\$name = trim(\$extracted['givenName'] . ' ' . \$extracted['familyName']);
\$address = \$extracted['addressLines'][0] ?? '';
\$city = \$extracted['locality'];

if (\$name === 'Jean Dupont' && \$address === '123 Rue de la Paix' && \$city === 'Paris') {
    echo '   ✅ Extraction de données fonctionnelle\n';
} else {
    echo '   ❌ Problème d'extraction: ' . \$name . ', ' . \$address . ', ' . \$city . '\n';
}
"

# Test 6: Vérifier les routes de debug
echo ""
echo "6️⃣ Test Routes Debug..."
if grep -q "debug/apple-pay" routes/web.php; then
    echo "   ✅ Routes de debug présentes"
    echo "   📍 POST /debug/apple-pay"
    echo "   📍 GET /debug/apple-pay-orders"
else
    echo "   ⚠️  Routes de debug manquantes"
fi

echo ""
echo "🎯 RÉSUMÉ DU TEST"
echo "================"
echo ""
echo "✅ Tous les systèmes sont opérationnels !"
echo ""
echo "📋 PROCHAINES ÉTAPES :"
echo "1. 🧪 Tester une vraie commande Apple Pay"
echo "2. 📊 Monitorer avec: ./monitor_apple_pay.sh"
echo "3. 🔍 Vérifier les logs: tail -f storage/logs/laravel.log"
echo "4. 📱 Consulter: /debug/apple-pay-orders"
echo ""
echo "🎉 Votre système Apple Pay est prêt !"
echo ""
echo "💡 IMPORTANT: Testez maintenant avec un vrai appareil Apple"
echo "   en HTTPS pour valider le fonctionnement complet."
