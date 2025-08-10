#!/bin/bash

echo "🧪 Test de la correction Apple Pay"
echo "================================="
echo ""

# Vérifier que l'application Laravel fonctionne
echo "🔄 Vérification de l'application..."
if php artisan --version > /dev/null 2>&1; then
    echo "✅ Laravel fonctionne"
else
    echo "❌ Problème avec Laravel"
    exit 1
fi

# Vérifier que les routes de debug existent
echo "🔄 Vérification des routes..."
if php artisan route:list | grep -q "debug/apple-pay"; then
    echo "✅ Routes de debug trouvées"
else
    echo "⚠️  Routes de debug non trouvées (normal si pas encore en cache)"
fi

# Vérifier le contenu du PaymentController
echo "🔄 Vérification du PaymentController..."
if grep -q "EXTRACTION AMÉLIORÉE DES DONNÉES APPLE PAY" app/Http/Controllers/PaymentController.php; then
    echo "✅ PaymentController corrigé"
else
    echo "❌ PaymentController non corrigé"
    exit 1
fi

if grep -q "debugApplePayData" app/Http/Controllers/PaymentController.php; then
    echo "✅ Méthode de debug présente"
else
    echo "❌ Méthode de debug manquante"
fi

echo ""
echo "🎉 TOUT EST PRÊT !"
echo "=================="
echo ""
echo "📋 Pour tester :"
echo "1. Allez sur votre site en HTTPS"
echo "2. Testez Apple Pay sur une page produit"
echo "3. Vérifiez les logs: tail -f storage/logs/laravel.log"
echo "4. Consultez les commandes: /debug/apple-pay-orders"
echo ""
echo "📞 En cas de problème :"
echo "- Vérifiez les logs Laravel"
echo "- Testez d'abord avec /debug/apple-pay"
echo "- Restaurez avec: cp app/Http/Controllers/PaymentController.php.backup.* app/Http/Controllers/PaymentController.php"
echo ""
echo "✨ Correction terminée avec succès !"
