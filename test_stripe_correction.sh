#!/bin/bash

echo "🔧 Test Correction Apple Pay - Données Stripe"
echo "============================================="
echo ""

# Test 1: Vérifier la syntaxe du PaymentController
echo "1️⃣ Vérification syntaxe PaymentController..."
php -l app/Http/Controllers/PaymentController.php
if [ $? -eq 0 ]; then
    echo "   ✅ Syntaxe correcte"
else
    echo "   ❌ Erreur de syntaxe détectée"
    exit 1
fi

# Test 2: Vérifier que la méthode updateOrderWithStripeData existe
echo ""
echo "2️⃣ Vérification nouvelle méthode..."
if grep -q "updateOrderWithStripeData" app/Http/Controllers/PaymentController.php; then
    echo "   ✅ Méthode updateOrderWithStripeData trouvée"
else
    echo "   ❌ Méthode updateOrderWithStripeData manquante"
fi

# Test 3: Vérifier les commandes avec données temporaires
echo ""
echo "3️⃣ Commandes avec données temporaires..."
php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';
\$kernel = \$app->make(Illuminate\Contracts\Console\Kernel::class);
\$kernel->bootstrap();

\$tempOrders = \App\Models\Order::where(function(\$q) {
    \$q->where('customer_email', 'temp@example.com')
      ->orWhere('customer_name', 'Temp Customer')
      ->orWhere('shipping_address_line_1', 'Temp Address');
})->get();

if (\$tempOrders->count() > 0) {
    echo '   ⚠️  ' . \$tempOrders->count() . ' commande(s) avec données temporaires:\n';
    foreach (\$tempOrders as \$order) {
        echo '   📦 ' . \$order->order_number . ' - ' . \$order->customer_name . ' (' . \$order->customer_email . ')\n';
        echo '      📍 ' . \$order->shipping_address_line_1 . ', ' . \$order->shipping_city . '\n';
        echo '      💰 ' . \$order->total_amount . '€ - ' . \$order->payment_status . '\n\n';
    }
} else {
    echo '   ✅ Aucune commande avec données temporaires\n';
}
"

# Test 4: Vérifier les logs récents
echo ""
echo "4️⃣ Logs récents Apple Pay..."
if [ -f "storage/logs/laravel.log" ]; then
    echo "   🔍 Recherche de logs Apple Pay..."
    recent_logs=$(grep "$(date +%Y-%m-%d)" storage/logs/laravel.log 2>/dev/null | grep -i "apple\|stripe" | wc -l)
    echo "   📊 Logs aujourd'hui: $recent_logs"
    
    if [ $recent_logs -gt 0 ]; then
        echo "   📋 Derniers logs pertinents:"
        grep "$(date +%Y-%m-%d)" storage/logs/laravel.log 2>/dev/null | grep -i "apple\|stripe" | tail -3 | while read line; do
            echo "   💬 $line"
        done
    fi
else
    echo "   ⚠️  Fichier de log non trouvé"
fi

echo ""
echo "🧪 INSTRUCTIONS DE TEST"
echo "======================"
echo ""
echo "Pour tester la correction :"
echo ""
echo "1. 📱 Passez une nouvelle commande Apple Pay depuis show.blade.php"
echo "2. 🔍 Surveillez les logs : tail -f storage/logs/laravel.log | grep 'updateOrderWithStripeData'"
echo "3. 📊 Vérifiez la commande : relancez ce script pour voir les données"
echo ""
echo "✅ Si la correction fonctionne, vous devriez voir :"
echo "   - Logs 'Updating order with Stripe session data'"
echo "   - Logs 'Order updated successfully with Stripe data'"
echo "   - Vraies données dans la commande (nom, email, adresse réels)"
echo ""
echo "❌ Si ça ne fonctionne pas :"
echo "   - Vérifiez que votre webhook Stripe est configuré"
echo "   - Vérifiez que l'URL de webhook pointe vers /payment/webhook"
echo "   - Consultez les logs d'erreur"
echo ""

# Test 5: Créer un monitoring en temps réel pour les tests
echo "5️⃣ Lancement du monitoring (optionnel)..."
echo ""
read -p "Voulez-vous lancer le monitoring en temps réel ? (y/N): " monitor

if [[ $monitor =~ ^[Yy]$ ]]; then
    echo ""
    echo "🚀 MONITORING EN TEMPS RÉEL"
    echo "==========================="
    echo ""
    echo "Surveillance des mises à jour de commandes..."
    echo "Faites maintenant votre test Apple Pay !"
    echo ""
    echo "Appuyez sur Ctrl+C pour arrêter"
    echo ""
    
    tail -f storage/logs/laravel.log | grep --line-buffered -E "(Apple Pay|updateOrderWithStripeData|Order updated successfully)" | while read line; do
        timestamp=$(date '+%H:%M:%S')
        if [[ $line == *"successfully"* ]]; then
            echo "[$timestamp] ✅ $line"
        elif [[ $line == *"error"* ]] || [[ $line == *"ERROR"* ]]; then
            echo "[$timestamp] ❌ $line"
        else
            echo "[$timestamp] ℹ️  $line"
        fi
    done
else
    echo ""
    echo "✨ Script terminé !"
    echo "Testez maintenant votre commande Apple Pay et relancez ce script pour voir les résultats."
fi
