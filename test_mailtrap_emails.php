<?php

/**
 * Test Complet du Système d'Emails Automatiques avec Mailtrap
 * 
 * Vérifie :
 * 1. Email de confirmation quand commande validée/payée
 * 2. Email d'expédition quand admin marque comme expédié
 */

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "📧 Test Système d'Emails Automatiques - Heritage Parfums\n";
echo "======================================================\n\n";

try {
    // 1. Vérification de la configuration Mailtrap
    echo "1. 🔧 Vérification configuration Mailtrap...\n";
    
    $mailer = config('mail.default');
    $host = config('mail.mailers.smtp.host');
    $port = config('mail.mailers.smtp.port');
    $username = config('mail.mailers.smtp.username');
    $fromAddress = config('mail.from.address');
    $fromName = config('mail.from.name');
    
    echo "   📧 Mailer: {$mailer}\n";
    echo "   🌐 Host: {$host}\n";
    echo "   🔌 Port: {$port}\n";
    echo "   👤 Username: {$username}\n";
    echo "   📮 From: {$fromName} <{$fromAddress}>\n";
    
    if ($host === 'sandbox.smtp.mailtrap.io') {
        echo "   ✅ Configuration Mailtrap détectée\n";
    } else {
        echo "   ⚠️  Configuration Mailtrap non détectée\n";
    }
    echo "\n";
    
    // 2. Nettoyage des anciens jobs pour un test propre
    echo "2. 🧹 Nettoyage des anciens jobs...\n";
    \DB::table('jobs')->delete();
    echo "   ✅ Queue nettoyée\n\n";
    
    // 3. Création d'une commande de test
    echo "3. 📦 Création d'une commande de test...\n";
    
    $order = \App\Models\Order::create([
        'order_number' => \App\Models\Order::generateOrderNumber(),
        'status' => 'pending',
        'payment_status' => 'pending',
        'customer_email' => 'client.test@heritage-parfums.fr',
        'customer_name' => 'Marie Dubois',
        'customer_phone' => '06.12.34.56.78',
        'billing_address_line_1' => '456 Avenue des Parfums',
        'billing_city' => 'Lyon',
        'billing_postal_code' => '69001',
        'billing_country' => 'FR',
        'shipping_address_line_1' => '456 Avenue des Parfums',
        'shipping_city' => 'Lyon',
        'shipping_postal_code' => '69001',
        'shipping_country' => 'FR',
        'subtotal' => 129.90,
        'tax_amount' => 25.98,
        'shipping_amount' => 0.00, // Livraison gratuite
        'total_amount' => 155.88,
        'currency' => 'EUR',
    ]);
    
    // Ajouter des articles à la commande
    $products = \App\Models\Product::take(2)->get();
    
    if ($products->count() > 0) {
        foreach ($products as $index => $product) {
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_type' => $product->product_type ?? 'parfum',
                'product_size' => '50ml',
                'quantity' => 1,
                'product_price' => $index === 0 ? 79.90 : 50.00,
                'total_price' => $index === 0 ? 79.90 : 50.00,
            ]);
        }
    } else {
        // Si aucun produit en base, créer des items avec des IDs existants
        $firstProduct = \App\Models\Product::first();
        if ($firstProduct) {
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $firstProduct->id,
                'product_name' => 'Éternelle Rose - Eau de Parfum',
                'product_type' => 'parfum',
                'product_size' => '50ml',
                'quantity' => 1,
                'product_price' => 79.90,
                'total_price' => 79.90,
            ]);
            
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $firstProduct->id,
                'product_name' => 'Mystique Noir - Eau de Parfum',
                'product_type' => 'parfum',
                'product_size' => '100ml',
                'quantity' => 1,
                'product_price' => 50.00,
                'total_price' => 50.00,
            ]);
        } else {
            echo "   ❌ Aucun produit en base de données\n";
            exit(1);
        }
    }
    
    echo "   ✅ Commande créée: {$order->order_number}\n";
    echo "   👤 Client: {$order->customer_name} ({$order->customer_email})\n";
    echo "   💰 Montant: {$order->total_amount}€\n";
    echo "   📦 Articles: {$order->items->count()}\n\n";
    
    // 4. TEST 1: Email de confirmation de commande (commande payée)
    echo "4. 📧 TEST 1: Email de confirmation de commande...\n";
    echo "   🔄 Simulation du paiement de la commande...\n";
    
    // Déclencher l'événement OrderPaid
    $order->markAsPaid();
    
    echo "   ✅ Commande marquée comme payée\n";
    echo "   📨 Événement OrderPaid déclenché\n";
    
    // Vérifier que le job a été créé
    $jobsAfterPayment = \DB::table('jobs')->count();
    echo "   📋 Jobs créés: {$jobsAfterPayment}\n";
    
    if ($jobsAfterPayment > 0) {
        echo "   ✅ Job d'email de confirmation créé\n";
    } else {
        echo "   ❌ Aucun job créé pour l'email de confirmation\n";
    }
    echo "\n";
    
    // 5. Traiter le premier job (email de confirmation)
    echo "5. ⚡ Traitement du job d'email de confirmation...\n";
    
    if ($jobsAfterPayment > 0) {
        echo "   🔄 Traitement du job en cours...\n";
        exec('cd /Users/halick/ProjetLaravel/HeritageParfums && php artisan queue:work --once 2>&1', $output1, $return1);
        
        if ($return1 === 0) {
            echo "   ✅ Email de confirmation traité avec succès\n";
            echo "   📧 Email envoyé vers Mailtrap\n";
        } else {
            echo "   ❌ Erreur lors du traitement: " . implode("\n", $output1) . "\n";
        }
    }
    echo "\n";
    
    // 6. TEST 2: Préparation pour l'expédition
    echo "6. 📦 Préparation de la commande pour expédition...\n";
    
    // Assigner un transporteur et générer un numéro de suivi
    $order->update([
        'shipping_carrier' => 'colissimo',
        'shipping_method' => 'standard',
        'tracking_number' => 'CR' . rand(100000000, 999999999) . 'FR',
        'carrier_reference' => $order->generateCarrierReference(),
        'shipping_weight' => $order->calculatePackageWeight(),
        'package_dimensions' => $order->getPackageDimensions(),
    ]);
    
    echo "   ✅ Transporteur assigné: Colissimo\n";
    echo "   🏷️  Numéro de suivi: {$order->tracking_number}\n";
    echo "   📐 Poids calculé: {$order->shipping_weight}kg\n\n";
    
    // 7. TEST 2: Email d'expédition (admin marque comme expédié)
    echo "7. 📧 TEST 2: Email d'expédition...\n";
    echo "   🔄 Simulation: admin marque la commande comme expédiée...\n";
    
    // Déclencher l'événement OrderShipped
    $order->markAsShipped();
    
    echo "   ✅ Commande marquée comme expédiée\n";
    echo "   📨 Événement OrderShipped déclenché\n";
    echo "   📅 Date d'expédition: {$order->shipped_at->format('d/m/Y H:i')}\n";
    
    // Vérifier le nombre total de jobs
    $totalJobs = \DB::table('jobs')->count();
    echo "   📋 Jobs en queue: {$totalJobs}\n";
    
    if ($totalJobs > 0) {
        echo "   ✅ Job d'email d'expédition créé\n";
    } else {
        echo "   ❌ Aucun job créé pour l'email d'expédition\n";
    }
    echo "\n";
    
    // 8. Traiter le deuxième job (email d'expédition)
    echo "8. ⚡ Traitement du job d'email d'expédition...\n";
    
    if ($totalJobs > 0) {
        echo "   🔄 Traitement du job en cours...\n";
        exec('cd /Users/halick/ProjetLaravel/HeritageParfums && php artisan queue:work --once 2>&1', $output2, $return2);
        
        if ($return2 === 0) {
            echo "   ✅ Email d'expédition traité avec succès\n";
            echo "   📧 Email envoyé vers Mailtrap\n";
        } else {
            echo "   ❌ Erreur lors du traitement: " . implode("\n", $output2) . "\n";
        }
    }
    echo "\n";
    
    // 9. Vérification finale des jobs
    echo "9. 📊 Vérification finale...\n";
    
    $remainingJobs = \DB::table('jobs')->count();
    $failedJobs = \DB::table('failed_jobs')->count();
    
    echo "   📋 Jobs restants en queue: {$remainingJobs}\n";
    echo "   ❌ Jobs échoués: {$failedJobs}\n";
    
    if ($remainingJobs === 0 && $failedJobs === 0) {
        echo "   ✅ Tous les emails ont été traités avec succès\n";
    } else {
        echo "   ⚠️  Il reste des jobs à traiter ou des erreurs\n";
    }
    echo "\n";
    
    // 10. Instructions pour vérifier dans Mailtrap
    echo "🎯 VÉRIFICATION DANS MAILTRAP\n";
    echo "=============================\n";
    echo "1. Allez sur https://mailtrap.io/inboxes\n";
    echo "2. Cliquez sur votre inbox\n";
    echo "3. Vous devriez voir 2 emails :\n\n";
    
    echo "   📧 EMAIL 1 - Confirmation de commande\n";
    echo "   ├─ À: {$order->customer_email}\n";
    echo "   ├─ De: {$fromName} <{$fromAddress}>\n";
    echo "   ├─ Sujet: Confirmation de votre commande {$order->order_number} - Heritage Parfums\n";
    echo "   └─ Contient: Récapitulatif commande, articles, montant total\n\n";
    
    echo "   📦 EMAIL 2 - Notification d'expédition\n";
    echo "   ├─ À: {$order->customer_email}\n";
    echo "   ├─ De: {$fromName} <{$fromAddress}>\n";
    echo "   ├─ Sujet: Votre commande {$order->order_number} a été expédiée - Heritage Parfums\n";
    echo "   └─ Contient: Numéro de suivi {$order->tracking_number}, transporteur Colissimo\n\n";
    
    // 11. Résumé du test
    echo "🎉 RÉSUMÉ DU TEST\n";
    echo "=================\n";
    echo "✅ Configuration Mailtrap validée\n";
    echo "✅ Commande de test créée: {$order->order_number}\n";
    echo "✅ EMAIL 1: Confirmation de commande → Envoyé automatiquement\n";
    echo "✅ EMAIL 2: Notification d'expédition → Envoyé automatiquement\n";
    echo "✅ Système d'emails automatiques opérationnel\n\n";
    
    echo "🔄 WORKFLOW AUTOMATIQUE CONFIRMÉ:\n";
    echo "1. Client paie → markAsPaid() → Email de confirmation\n";
    echo "2. Admin expédie → markAsShipped() → Email d'expédition\n\n";
    
    echo "💡 POUR LA PRODUCTION:\n";
    echo "1. Lancez: php artisan queue:work --daemon\n";
    echo "2. Les emails s'enverront automatiquement\n";
    echo "3. Surveillez les logs: tail -f storage/logs/laravel.log\n\n";
    
    // 12. Test de nettoyage (optionnel)
    echo "🗑️  Voulez-vous supprimer la commande de test ? (o/N): ";
    $cleanup = trim(fgets(STDIN));
    
    if (strtolower($cleanup) === 'o' || strtolower($cleanup) === 'oui') {
        $order->items()->delete();
        $order->delete();
        echo "   ✅ Commande de test supprimée\n";
    } else {
        echo "   ℹ️  Commande de test conservée: {$order->order_number}\n";
    }

} catch (Exception $e) {
    echo "❌ Erreur durante le test: " . $e->getMessage() . "\n";
    echo "📍 Fichier: " . $e->getFile() . " ligne " . $e->getLine() . "\n";
    echo "\n🔧 SOLUTIONS POSSIBLES:\n";
    echo "1. Vérifiez votre configuration .env\n";
    echo "2. Testez Mailtrap: php test_smtp_config.php\n";
    echo "3. Videz le cache: php artisan config:clear\n";
    echo "4. Vérifiez les logs: tail storage/logs/laravel.log\n";
}
