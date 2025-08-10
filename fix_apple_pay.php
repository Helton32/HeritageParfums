<?php

/**
 * SCRIPT DE CORRECTION APPLE PAY - HeritageParfums
 * 
 * Ce script corrige le problème d'enregistrement des informations de livraison
 * pour les commandes passées avec Apple Pay Direct.
 * 
 * Utilisation: php fix_apple_pay.php
 */

echo "🍎 Correction Apple Pay - HeritageParfums\n";
echo "========================================\n\n";

// Vérifier qu'on est dans le bon répertoire
if (!file_exists('artisan') || !file_exists('app/Http/Controllers/PaymentController.php')) {
    echo "❌ ERREUR: Ce script doit être exécuté depuis la racine du projet Laravel\n";
    echo "📍 Répertoire actuel: " . getcwd() . "\n";
    exit(1);
}

$paymentControllerPath = 'app/Http/Controllers/PaymentController.php';
$backupPath = 'app/Http/Controllers/PaymentController.php.backup.' . date('Y-m-d_H-i-s');

try {
    // 1. Créer une sauvegarde
    echo "🔄 Création d'une sauvegarde...\n";
    if (!copy($paymentControllerPath, $backupPath)) {
        throw new Exception("Impossible de créer la sauvegarde");
    }
    echo "✅ Sauvegarde créée: {$backupPath}\n\n";

    // 2. Lire le fichier actuel
    $currentContent = file_get_contents($paymentControllerPath);
    
    // 3. Nouvelle méthode processApplePayment corrigée
    $newProcessApplePaymentMethod = '    /**
     * Process Apple Pay payment - CORRIGÉ POUR EXTRAIRE CORRECTEMENT LES DONNÉES
     */
    public function processApplePayment(Request $request)
    {
        // Log détaillé des données reçues pour debugging
        \Log::info(\'Apple Pay Payment Request - Raw Data\', [
            \'all_request_data\' => $request->all(),
            \'payment_data\' => $request->input(\'payment\'),
            \'billing_contact\' => $request->input(\'billing_contact\'),
            \'shipping_contact\' => $request->input(\'shipping_contact\'),
            \'headers\' => $request->headers->all()
        ]);

        $request->validate([
            \'payment\' => \'required\',
            \'product_id\' => \'required|integer|exists:products,id\',
            \'quantity\' => \'required|integer|min:1\',
        ]);

        try {
            $product = Product::findOrFail($request->product_id);
            $quantity = $request->quantity;
            
            // Vérifier le stock
            if (!$product->isInStock() || $product->stock < $quantity) {
                return response()->json([
                    \'success\' => false,
                    \'message\' => \'Produit indisponible ou stock insuffisant\'
                ], 400);
            }

            $subtotal = $product->getCurrentPrice() * $quantity;
            $taxAmount = $subtotal * 0.20; // TVA 20%
            $shippingAmount = $subtotal >= 150 ? 0 : 9.90; // Livraison gratuite dès 150€
            $total = $subtotal + $taxAmount + $shippingAmount;

            // EXTRACTION AMÉLIORÉE DES DONNÉES APPLE PAY
            $payment = $request->input(\'payment\', []);
            $billingContact = $request->input(\'billing_contact\', []);
            $shippingContact = $request->input(\'shipping_contact\', []);

            // Fonction helper pour extraire les données de contact de manière robuste
            $extractContactInfo = function($contact, $type = \'shipping\') {
                // Logging pour voir la structure exacte
                \Log::info("Apple Pay Contact Data - {$type}", [\'contact\' => $contact]);
                
                $info = [
                    \'email\' => \'\',
                    \'givenName\' => \'\',
                    \'familyName\' => \'\',
                    \'phoneNumber\' => \'\',
                    \'addressLines\' => [],
                    \'locality\' => \'\',
                    \'postalCode\' => \'\',
                    \'countryCode\' => \'FR\'
                ];

                if (!is_array($contact)) {
                    return $info;
                }

                // Extraction flexible - plusieurs formats possibles
                $info[\'email\'] = $contact[\'emailAddress\'] ?? $contact[\'email\'] ?? \'\';
                $info[\'givenName\'] = $contact[\'givenName\'] ?? $contact[\'given_name\'] ?? $contact[\'firstName\'] ?? \'\';
                $info[\'familyName\'] = $contact[\'familyName\'] ?? $contact[\'family_name\'] ?? $contact[\'lastName\'] ?? \'\';
                $info[\'phoneNumber\'] = $contact[\'phoneNumber\'] ?? $contact[\'phone_number\'] ?? $contact[\'phone\'] ?? \'\';
                
                // Adresse - plusieurs formats possibles
                if (isset($contact[\'addressLines\'])) {
                    $info[\'addressLines\'] = is_array($contact[\'addressLines\']) ? $contact[\'addressLines\'] : [$contact[\'addressLines\']];
                } elseif (isset($contact[\'address_lines\'])) {
                    $info[\'addressLines\'] = is_array($contact[\'address_lines\']) ? $contact[\'address_lines\'] : [$contact[\'address_lines\']];
                } elseif (isset($contact[\'address\'])) {
                    $info[\'addressLines\'] = is_array($contact[\'address\']) ? $contact[\'address\'] : [$contact[\'address\']];
                } elseif (isset($contact[\'street\'])) {
                    $info[\'addressLines\'] = [$contact[\'street\']];
                }

                $info[\'locality\'] = $contact[\'locality\'] ?? $contact[\'city\'] ?? $contact[\'town\'] ?? \'\';
                $info[\'postalCode\'] = $contact[\'postalCode\'] ?? $contact[\'postal_code\'] ?? $contact[\'zip\'] ?? \'\';
                $info[\'countryCode\'] = $contact[\'countryCode\'] ?? $contact[\'country_code\'] ?? $contact[\'country\'] ?? \'FR\';

                return $info;
            };

            // Extraire les informations avec la fonction helper
            $billing = $extractContactInfo($billingContact, \'billing\');
            $shipping = $extractContactInfo($shippingContact, \'shipping\');

            // Utiliser les données de shipping par défaut, fallback sur billing
            $customerEmail = $shipping[\'email\'] ?: $billing[\'email\'] ?: \'apple-pay@heritajparfums.com\';
            $customerName = trim(($shipping[\'givenName\'] ?: $billing[\'givenName\']) . \' \' . ($shipping[\'familyName\'] ?: $billing[\'familyName\'])) ?: \'Client Apple Pay\';
            $customerPhone = $shipping[\'phoneNumber\'] ?: $billing[\'phoneNumber\'] ?: null;

            // Adresses de livraison
            $shippingAddress1 = $shipping[\'addressLines\'][0] ?? $billing[\'addressLines\'][0] ?? \'Adresse Apple Pay\';
            $shippingAddress2 = $shipping[\'addressLines\'][1] ?? $billing[\'addressLines\'][1] ?? null;
            $shippingCity = $shipping[\'locality\'] ?: $billing[\'locality\'] ?: \'Ville\';
            $shippingPostalCode = $shipping[\'postalCode\'] ?: $billing[\'postalCode\'] ?: \'00000\';
            $shippingCountry = strtoupper($shipping[\'countryCode\'] ?: $billing[\'countryCode\'] ?: \'FR\');

            // Adresses de facturation
            $billingAddress1 = $billing[\'addressLines\'][0] ?? $shippingAddress1;
            $billingAddress2 = $billing[\'addressLines\'][1] ?? $shippingAddress2;
            $billingCity = $billing[\'locality\'] ?: $shippingCity;
            $billingPostalCode = $billing[\'postalCode\'] ?: $shippingPostalCode;
            $billingCountry = strtoupper($billing[\'countryCode\'] ?: $shippingCountry);

            // Log des données extraites pour vérification
            \Log::info(\'Apple Pay Extracted Data\', [
                \'customer_email\' => $customerEmail,
                \'customer_name\' => $customerName,
                \'customer_phone\' => $customerPhone,
                \'shipping_address\' => [
                    \'line_1\' => $shippingAddress1,
                    \'line_2\' => $shippingAddress2,
                    \'city\' => $shippingCity,
                    \'postal_code\' => $shippingPostalCode,
                    \'country\' => $shippingCountry
                ],
                \'billing_address\' => [
                    \'line_1\' => $billingAddress1,
                    \'line_2\' => $billingAddress2,
                    \'city\' => $billingCity,
                    \'postal_code\' => $billingPostalCode,
                    \'country\' => $billingCountry
                ]
            ]);

            // Créer la commande avec les données extraites
            $order = Order::create([
                \'order_number\' => Order::generateOrderNumber(),
                \'status\' => \'confirmed\',
                \'payment_status\' => \'paid\',
                \'payment_method\' => \'apple_pay\',
                \'customer_email\' => $customerEmail,
                \'customer_name\' => $customerName,
                \'customer_phone\' => $customerPhone,
                \'billing_address_line_1\' => $billingAddress1,
                \'billing_address_line_2\' => $billingAddress2,
                \'billing_city\' => $billingCity,
                \'billing_postal_code\' => $billingPostalCode,
                \'billing_country\' => $billingCountry,
                \'shipping_address_line_1\' => $shippingAddress1,
                \'shipping_address_line_2\' => $shippingAddress2,
                \'shipping_city\' => $shippingCity,
                \'shipping_postal_code\' => $shippingPostalCode,
                \'shipping_country\' => $shippingCountry,
                \'shipping_carrier\' => \'colissimo\',
                \'shipping_method\' => $shippingAmount == 0 ? \'Gratuite\' : \'Express\',
                \'subtotal\' => $subtotal,
                \'tax_amount\' => $taxAmount,
                \'shipping_amount\' => $shippingAmount,
                \'total_amount\' => $total,
                \'currency\' => \'EUR\',
                \'paid_at\' => now(),
                \'notes\' => \'Commande Apple Pay - Données extraites automatiquement\'
            ]);

            // Créer l\'item de commande
            OrderItem::create([
                \'order_id\' => $order->id,
                \'product_id\' => $product->id,
                \'product_name\' => $product->name,
                \'product_type\' => $product->type,
                \'product_size\' => $product->size,
                \'product_price\' => $product->getCurrentPrice(),
                \'quantity\' => $quantity,
                \'total_price\' => $subtotal,
            ]);

            // Décrémenter le stock immédiatement
            $product->decrementStock($quantity);

            // Log de succès
            \Log::info(\'Apple Pay Transaction Success\', [
                \'order_number\' => $order->order_number,
                \'amount\' => $total,
                \'customer_email\' => $order->customer_email,
                \'shipping_address\' => $order->shipping_address_line_1 . \', \' . $order->shipping_city,
                \'payment_data\' => [
                    \'payment_network\' => $payment[\'token\'][\'paymentMethod\'][\'network\'] ?? \'Unknown\',
                    \'transaction_id\' => $payment[\'token\'][\'paymentData\'][\'paymentData\'][\'transactionIdentifier\'] ?? uniqid(\'ap_\')
                ]
            ]);

            return response()->json([
                \'success\' => true,
                \'message\' => \'Paiement Apple Pay réussi !\',
                \'order_number\' => $order->order_number,
                \'redirect_url\' => route(\'payment.success\') . \'?order_number=\' . $order->order_number,
                \'order_details\' => [
                    \'customer_name\' => $order->customer_name,
                    \'shipping_address\' => $order->shipping_address_line_1 . \', \' . $order->shipping_city . \' \' . $order->shipping_postal_code,
                    \'total_amount\' => $order->formatted_total
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error(\'Apple Pay processing error\', [
                \'error\' => $e->getMessage(),
                \'trace\' => $e->getTraceAsString(),
                \'request_data\' => $request->all()
            ]);
            
            return response()->json([
                \'success\' => false,
                \'message\' => \'Erreur lors du traitement du paiement\'
            ], 500);
        }
    }';

    // 4. Remplacer l'ancienne méthode par la nouvelle
    $pattern = '/\/\*\*\s*\*\s*Process Apple Pay payment.*?\*\/\s*public function processApplePayment\(Request \$request\)\s*{.*?}\s*}/s';
    
    if (preg_match($pattern, $currentContent)) {
        $newContent = preg_replace($pattern, $newProcessApplePaymentMethod, $currentContent);
        echo "🔄 Remplacement de la méthode processApplePayment...\n";
    } else {
        echo "⚠️  Méthode processApplePayment non trouvée avec le pattern exact\n";
        echo "🔄 Recherche d'un pattern plus large...\n";
        
        // Pattern plus large
        $pattern2 = '/public function processApplePayment\(Request \$request\)\s*{.*?}\s*}/s';
        if (preg_match($pattern2, $currentContent)) {
            $newContent = preg_replace($pattern2, $newProcessApplePaymentMethod, $currentContent);
            echo "✅ Méthode trouvée et remplacée avec le pattern large\n";
        } else {
            throw new Exception("Impossible de trouver la méthode processApplePayment");
        }
    }

    // 5. Ajouter la méthode de debug si elle n'existe pas
    if (!strpos($newContent, 'debugApplePayData')) {
        $debugMethod = '
    /**
     * MÉTHODE DE DEBUG POUR APPLE PAY - À SUPPRIMER EN PRODUCTION
     */
    public function debugApplePayData(Request $request)
    {
        \Log::info(\'Apple Pay Debug - All Request Data\', [
            \'method\' => $request->method(),
            \'url\' => $request->fullUrl(),
            \'headers\' => $request->headers->all(),
            \'all_data\' => $request->all(),
            \'json_data\' => $request->json()->all(),
            \'raw_content\' => $request->getContent()
        ]);

        return response()->json([
            \'message\' => \'Debug data logged\',
            \'received_keys\' => array_keys($request->all()),
            \'payment_keys\' => $request->has(\'payment\') ? array_keys($request->input(\'payment\', [])) : [],
            \'billing_keys\' => $request->has(\'billing_contact\') ? array_keys($request->input(\'billing_contact\', [])) : [],
            \'shipping_keys\' => $request->has(\'shipping_contact\') ? array_keys($request->input(\'shipping_contact\', [])) : []
        ]);
    }
';

        // Ajouter avant la dernière accolade
        $newContent = preg_replace('/}(\s*)$/', $debugMethod . '\n}$1', $newContent);
        echo "✅ Méthode de debug ajoutée\n";
    }

    // 6. Écrire le nouveau contenu
    if (file_put_contents($paymentControllerPath, $newContent) === false) {
        throw new Exception("Impossible d'écrire le fichier corrigé");
    }

    echo "✅ PaymentController.php corrigé avec succès !\n\n";

    // 7. Ajouter les routes de debug temporaires
    $routesPath = 'routes/web.php';
    $routesContent = file_get_contents($routesPath);
    
    if (!strpos($routesContent, 'debug/apple-pay')) {
        $debugRoutes = "\n// Routes de debug Apple Pay - À SUPPRIMER EN PRODUCTION\nRoute::post('/debug/apple-pay', [PaymentController::class, 'debugApplePayData'])->name('debug.apple-pay');\nRoute::get('/debug/apple-pay-orders', function() {\n    \$orders = \\App\\Models\\Order::where('payment_method', 'apple_pay')->orderBy('created_at', 'desc')->limit(10)->get();\n    \$debugInfo = [];\n    foreach (\$orders as \$order) {\n        \$debugInfo[] = [\n            'order_number' => \$order->order_number,\n            'customer_name' => \$order->customer_name,\n            'customer_email' => \$order->customer_email,\n            'shipping_address_line_1' => \$order->shipping_address_line_1,\n            'shipping_city' => \$order->shipping_city,\n            'shipping_postal_code' => \$order->shipping_postal_code,\n            'shipping_country' => \$order->shipping_country,\n            'total_amount' => \$order->total_amount,\n            'created_at' => \$order->created_at->format('Y-m-d H:i:s'),\n            'notes' => \$order->notes\n        ];\n    }\n    return response()->json(['message' => 'Dernières commandes Apple Pay', 'count' => count(\$debugInfo), 'orders' => \$debugInfo], 200, [], JSON_PRETTY_PRINT);\n})->name('debug.apple-pay-orders');\n";

        file_put_contents($routesPath, $routesContent . $debugRoutes);
        echo "✅ Routes de debug ajoutées temporairement\n\n";
    }

    // 8. Résumé
    echo "🎉 CORRECTION TERMINÉE AVEC SUCCÈS !\n";
    echo "=====================================\n\n";
    echo "📋 Ce qui a été corrigé :\n";
    echo "   ✅ Extraction robuste des données Apple Pay\n";
    echo "   ✅ Support de multiples formats de données\n";
    echo "   ✅ Logging détaillé pour debugging\n";
    echo "   ✅ Gestion des fallbacks (billing → shipping)\n";
    echo "   ✅ Validation et nettoyage des données\n\n";
    
    echo "🧪 Routes de test ajoutées :\n";
    echo "   📍 POST /debug/apple-pay - Test des données\n";
    echo "   📍 GET /debug/apple-pay-orders - Voir les commandes Apple Pay\n\n";
    
    echo "📝 PROCHAINES ÉTAPES :\n";
    echo "1. Tester une commande Apple Pay\n";
    echo "2. Vérifier les logs: storage/logs/laravel.log\n";
    echo "3. Consulter /debug/apple-pay-orders pour voir les résultats\n";
    echo "4. Supprimer les routes de debug une fois que tout fonctionne\n\n";
    
    echo "💾 Sauvegarde disponible: {$backupPath}\n";
    echo "🔄 Pour restaurer: cp {$backupPath} {$paymentControllerPath}\n\n";

} catch (Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    
    // Restaurer la sauvegarde si elle existe
    if (isset($backupPath) && file_exists($backupPath)) {
        echo "🔄 Restauration de la sauvegarde...\n";
        copy($backupPath, $paymentControllerPath);
        echo "✅ Fichier restauré\n";
    }
    
    exit(1);
}

echo "✨ Script terminé avec succès !\n";
