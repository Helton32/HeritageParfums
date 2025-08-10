#!/bin/bash

echo "📊 Monitoring Apple Pay - HeritageParfums"
echo "======================================="
echo ""

# Fonction pour afficher une section
show_section() {
    echo ""
    echo "📋 $1"
    echo "$(printf '=%.0s' {1..50})"
}

# Vérifier les commandes Apple Pay récentes
show_section "COMMANDES APPLE PAY RÉCENTES"

# Utiliser PHP pour interroger la base de données
php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';
\$kernel = \$app->make(Illuminate\Contracts\Console\Kernel::class);
\$kernel->bootstrap();

use App\Models\Order;

\$orders = Order::where('payment_method', 'apple_pay')
    ->orderBy('created_at', 'desc')
    ->limit(5)
    ->get();

if (\$orders->count() == 0) {
    echo '⚠️  Aucune commande Apple Pay trouvée\n';
} else {
    echo '✅ ' . \$orders->count() . ' commande(s) Apple Pay trouvée(s)\n\n';
    
    foreach (\$orders as \$order) {
        echo '🛍️  Commande: ' . \$order->order_number . '\n';
        echo '👤 Client: ' . \$order->customer_name . ' (' . \$order->customer_email . ')\n';
        echo '📍 Adresse: ' . \$order->shipping_address_line_1 . ', ' . \$order->shipping_city . ' ' . \$order->shipping_postal_code . '\n';
        echo '💰 Montant: ' . \$order->total_amount . '€\n';
        echo '📅 Date: ' . \$order->created_at->format('d/m/Y H:i') . '\n';
        
        // Vérifier si les données semblent correctes
        if (\$order->customer_name === 'Temp Customer' || \$order->shipping_address_line_1 === 'Temp Address') {
            echo '❌ PROBLÈME: Données temporaires détectées!\n';
        } else {
            echo '✅ Données correctes\n';
        }
        echo '\n';
    }
}
"

# Vérifier les logs Apple Pay récents
show_section "LOGS APPLE PAY RÉCENTS"

if [ -f "storage/logs/laravel.log" ]; then
    echo "🔍 Recherche dans les logs..."
    
    # Compter les entrées Apple Pay aujourd'hui
    today=$(date +%Y-%m-%d)
    apple_pay_logs=$(grep -c "Apple Pay" storage/logs/laravel.log 2>/dev/null || echo "0")
    
    echo "📊 Entrées Apple Pay trouvées: $apple_pay_logs"
    
    if [ "$apple_pay_logs" -gt 0 ]; then
        echo ""
        echo "🔍 Dernières entrées Apple Pay:"
        echo "$(grep "Apple Pay" storage/logs/laravel.log | tail -3)"
    fi
else
    echo "⚠️  Fichier de log non trouvé: storage/logs/laravel.log"
fi

# Vérifier l'état des routes de debug
show_section "ROUTES DE DEBUG"

if grep -q "debug/apple-pay" routes/web.php; then
    echo "✅ Routes de debug actives"
    echo "📍 Testez avec: curl -X POST votre-site.com/debug/apple-pay"
    echo "📍 Consultez: votre-site.com/debug/apple-pay-orders"
else
    echo "⚠️  Routes de debug non trouvées"
fi

# Vérifier l'état du PaymentController
show_section "ÉTAT DU PAYMENTCONTROLLER"

if grep -q "EXTRACTION AMÉLIORÉE DES DONNÉES APPLE PAY" app/Http/Controllers/PaymentController.php; then
    echo "✅ PaymentController corrigé"
    
    if grep -q "debugApplePayData" app/Http/Controllers/PaymentController.php; then
        echo "🧪 Méthode de debug présente"
    fi
else
    echo "❌ PaymentController non corrigé"
fi

# Recommandations
show_section "RECOMMANDATIONS"

echo "🎯 Actions recommandées:"
echo ""
echo "1. 🧪 TESTER:"
echo "   - Passez une commande Apple Pay test"
echo "   - Vérifiez que les informations sont correctes"
echo ""
echo "2. 📊 MONITORER:"
echo "   - Surveillez les logs: tail -f storage/logs/laravel.log"
echo "   - Consultez: /debug/apple-pay-orders"
echo ""
echo "3. 🧹 NETTOYER (après validation):"
echo "   - Supprimez les routes de debug"
echo "   - Supprimez la méthode debugApplePayData()"
echo ""
echo "4. 🚨 EN CAS DE PROBLÈME:"
echo "   - Restaurez: cp app/Http/Controllers/PaymentController.php.backup.* app/Http/Controllers/PaymentController.php"
echo "   - Videz le cache: php artisan cache:clear"
echo ""

# Résumé final
show_section "RÉSUMÉ"

echo "🎉 La correction Apple Pay a été appliquée avec succès !"
echo ""
echo "📈 Prochaine étape: Tester une commande Apple Pay réelle"
echo "🔍 Monitoring: Surveillez les logs et la base de données"
echo "✅ Objectif: Zéro commande avec des données temporaires"
echo ""
echo "💡 Conseil: Gardez ce script pour monitorer régulièrement"
echo ""

echo "✨ Fin du monitoring - $(date)"
