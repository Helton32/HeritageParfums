#!/bin/bash

echo "🔍 Test Temps Réel Apple Pay - HeritageParfums"
echo "============================================="
echo ""

echo "Ce script vous aide à tester Apple Pay et voir les résultats immédiatement."
echo ""

# Fonction pour afficher les dernières commandes
show_recent_orders() {
    echo "📋 Dernières commandes Apple Pay:"
    echo "================================="
    
    php -r "
    require 'vendor/autoload.php';
    \$app = require 'bootstrap/app.php';
    \$kernel = \$app->make(Illuminate\Contracts\Console\Kernel::class);
    \$kernel->bootstrap();
    
    \$orders = \App\Models\Order::where('payment_method', 'apple_pay')
        ->orderBy('created_at', 'desc')
        ->limit(3)
        ->get();
    
    if (\$orders->count() == 0) {
        echo '⚠️  Aucune commande Apple Pay trouvée\n\n';
        return;
    }
    
    foreach (\$orders as \$i => \$order) {
        echo '🛍️  Commande #' . (\$i + 1) . ': ' . \$order->order_number . '\n';
        echo '👤 Client: ' . \$order->customer_name . ' (' . \$order->customer_email . ')\n';
        echo '📍 Adresse: ' . \$order->shipping_address_line_1 . ', ' . \$order->shipping_city . ' ' . \$order->shipping_postal_code . '\n';
        echo '💰 Montant: ' . \$order->total_amount . '€\n';
        echo '📅 Date: ' . \$order->created_at->format('d/m/Y H:i:s') . '\n';
        
        // Vérifier la qualité des données
        \$issues = [];
        if (\$order->customer_name === 'Temp Customer') \$issues[] = 'Nom temporaire';
        if (\$order->customer_email === 'temp@example.com') \$issues[] = 'Email temporaire';
        if (\$order->shipping_address_line_1 === 'Temp Address') \$issues[] = 'Adresse temporaire';
        if (\$order->shipping_city === 'Temp City') \$issues[] = 'Ville temporaire';
        
        if (empty(\$issues)) {
            echo '✅ Données PARFAITES !\n';
        } else {
            echo '❌ PROBLÈMES: ' . implode(', ', \$issues) . '\n';
        }
        echo '\n';
    }
    "
}

# Fonction pour surveiller les logs
monitor_logs() {
    echo "📊 Surveillance des logs Apple Pay..."
    echo "===================================="
    echo ""
    
    # Compter les logs Apple Pay
    total_logs=$(grep -c "Apple Pay" storage/logs/laravel.log 2>/dev/null || echo "0")
    echo "📈 Total logs Apple Pay: $total_logs"
    
    # Logs d'aujourd'hui
    today=$(date +%Y-%m-%d)
    today_logs=$(grep "$today" storage/logs/laravel.log 2>/dev/null | grep -c "Apple Pay" || echo "0")
    echo "📅 Logs aujourd'hui: $today_logs"
    
    # Derniers logs
    echo ""
    echo "🔍 Derniers logs Apple Pay:"
    echo "------------------------"
    grep "Apple Pay" storage/logs/laravel.log 2>/dev/null | tail -3 | while read line; do
        if [[ $line == *"SUCCESS"* ]] || [[ $line == *"Transaction Success"* ]]; then
            echo "✅ $line"
        elif [[ $line == *"ERROR"* ]] || [[ $line == *"error"* ]]; then
            echo "❌ $line"
        else
            echo "ℹ️  $line"
        fi
    done
    echo ""
}

# Menu principal
while true; do
    echo "🎯 QUE VOULEZ-VOUS FAIRE ?"
    echo "========================="
    echo "1. 📋 Voir les dernières commandes Apple Pay"
    echo "2. 📊 Surveiller les logs"
    echo "3. 🔄 Actualiser (voir les deux)"
    echo "4. 🧪 Afficher les infos de test"
    echo "5. 🚀 Lancer le monitoring continu"
    echo "6. ❌ Quitter"
    echo ""
    read -p "Votre choix (1-6): " choice
    
    case $choice in
        1)
            clear
            show_recent_orders
            echo ""
            read -p "Appuyez sur Entrée pour continuer..."
            clear
            ;;
        2)
            clear
            monitor_logs
            echo ""
            read -p "Appuyez sur Entrée pour continuer..."
            clear
            ;;
        3)
            clear
            show_recent_orders
            monitor_logs
            echo ""
            read -p "Appuyez sur Entrée pour continuer..."
            clear
            ;;
        4)
            clear
            echo "🧪 INFORMATIONS DE TEST"
            echo "======================"
            echo ""
            echo "📱 Pour tester Apple Pay:"
            echo "1. Utilisez un appareil Apple (iPhone, iPad, Mac avec Touch ID/Face ID)"
            echo "2. Allez sur votre site en HTTPS"
            echo "3. Choisissez un produit et cliquez 'Acheter avec Apple Pay'"
            echo "4. Complétez la transaction"
            echo "5. Revenez ici et choisissez l'option 3 pour voir les résultats"
            echo ""
            echo "🔗 Routes de debug disponibles:"
            echo "- GET /debug/apple-pay-orders (voir toutes les commandes)"
            echo "- POST /debug/apple-pay (test des données)"
            echo ""
            echo "📊 Logs à surveiller:"
            echo "- 'Apple Pay Transaction Success' = ✅ Succès"
            echo "- 'Apple Pay processing error' = ❌ Erreur"
            echo ""
            read -p "Appuyez sur Entrée pour continuer..."
            clear
            ;;
        5)
            clear
            echo "🚀 MONITORING CONTINU"
            echo "===================="
            echo ""
            echo "Surveillance des logs Apple Pay en temps réel..."
            echo "Appuyez sur Ctrl+C pour arrêter"
            echo ""
            
            # Monitoring continu
            tail -f storage/logs/laravel.log | grep --line-buffered "Apple Pay" | while read line; do
                timestamp=$(date '+%H:%M:%S')
                if [[ $line == *"SUCCESS"* ]] || [[ $line == *"Transaction Success"* ]]; then
                    echo "[$timestamp] ✅ $line"
                elif [[ $line == *"ERROR"* ]] || [[ $line == *"error"* ]]; then
                    echo "[$timestamp] ❌ $line"
                else
                    echo "[$timestamp] ℹ️  $line"
                fi
            done
            
            clear
            ;;
        6)
            echo ""
            echo "✨ Merci d'avoir utilisé le testeur Apple Pay !"
            echo ""
            echo "📋 RAPPEL: Après un test réussi avec de vraies données,"
            echo "   n'oubliez pas de supprimer les routes de debug en production."
            echo ""
            exit 0
            ;;
        *)
            echo "❌ Choix invalide. Utilisez 1-6."
            sleep 1
            clear
            ;;
    esac
done
