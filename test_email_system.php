<?php

/**
 * Script de test pour le système d'emails automatiques
 * 
 * Ce script permet de tester l'envoi automatique d'emails pour :
 * - Confirmation de commande (quand le paiement est reçu)
 * - Notification d'expédition (quand la commande est marquée comme expédiée)
 * 
 * Usage:
 * php test_email_system.php
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🧪 Test du système d'emails automatiques Heritage Parfums\n";
echo "======================================================\n\n";

try {
    // 1. Créer une commande de test
    echo "1. Création d'une commande de test...\n";
    
    $order = Order::create([
        'order_number' => Order::generateOrderNumber(),
        'status' => 'pending',
        'payment_status' => 'pending',
        'customer_email' => 'test@heritage-parfums.fr',
        'customer_name' => 'Jean Dupont',
        'customer_phone' => '01.23.45.67.89',
        'billing_address_line_1' => '123 Rue de la Parfumerie',
        'billing_city' => 'Paris',
        'billing_postal_code' => '75001',
        'billing_country' => 'FR',
        'shipping_address_line_1' => '123 Rue de la Parfumerie',
        'shipping_city' => 'Paris',
        'shipping_postal_code' => '75001',
        'shipping_country' => 'FR',
        'subtotal' => 89.90,
        'tax_amount' => 17.98,
        'shipping_amount' => 9.90,
        'total_amount' => 117.78,
        'currency' => 'EUR',
    ]);
    
    // Ajouter des articles à la commande
    $products = Product::take(2)->get();
    
    if ($products->count() > 0) {
        foreach ($products->take(2) as $index => $product) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_type' => $product->product_type ?? 'parfum',
                'product_size' => '50ml',
                'quantity' => 1,
                'product_price' => 44.95,
                'total_price' => 44.95,
            ]);
        }
    } else {
        // Si pas de produits en DB, créer des items de test
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => null,
            'product_name' => 'Eau de Parfum Heritage Classique',
            'product_type' => 'parfum',
            'product_size' => '50ml',
            'quantity' => 1,
            'product_price' => 44.95,
            'total_price' => 44.95,
        ]);
        
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => null,
            'product_name' => 'Eau de Parfum Heritage Intense',
            'product_type' => 'parfum',
            'product_size' => '100ml',
            'quantity' => 1,
            'product_price' => 44.95,
            'total_price' => 44.95,
        ]);
    }
    
    echo "   ✅ Commande créée: {$order->order_number}\n";
    echo "   📧 Email client: {$order->customer_email}\n\n";
    
    // 2. Test de l'email de confirmation (commande payée)
    echo "2. Test de l'email de confirmation de commande...\n";
    echo "   🔄 Marquage de la commande comme payée...\n";
    
    $order->markAsPaid();
    
    echo "   ✅ Commande marquée comme payée\n";
    echo "   📨 Email de confirmation en cours d'envoi...\n";
    echo "   ℹ️  Vérifiez vos logs Laravel pour voir l'email (MAIL_MAILER=log)\n\n";
    
    // Attendre un peu pour la queue
    sleep(2);
    
    // 3. Test de l'email d'expédition
    echo "3. Test de l'email d'expédition...\n";
    echo "   🔄 Marquage de la commande comme expédiée...\n";
    
    $order->tracking_number = 'HP-TRACKING-' . rand(100000, 999999);
    $order->shipping_carrier = 'colissimo';
    $order->save();
    
    $order->markAsShipped();
    
    echo "   ✅ Commande marquée comme expédiée\n";
    echo "   📨 Email d'expédition en cours d'envoi...\n";
    echo "   🔗 Numéro de suivi: {$order->tracking_number}\n";
    echo "   ℹ️  Vérifiez vos logs Laravel pour voir l'email (MAIL_MAILER=log)\n\n";
    
    // 4. Informations sur les jobs
    echo "4. Informations sur les jobs en queue...\n";
    
    $jobsCount = DB::table('jobs')->count();
    echo "   📋 Nombre de jobs en attente: {$jobsCount}\n";
    
    if ($jobsCount > 0) {
        echo "   ⚠️  Pour traiter les jobs, lancez: php artisan queue:work\n";
    }
    
    echo "\n🎉 Test terminé avec succès !\n";
    echo "\n📝 Pour voir les emails générés :\n";
    echo "   - Regardez les logs : storage/logs/laravel.log\n";
    echo "   - Ou configurez un vrai SMTP dans le .env\n";
    echo "\n🔧 Pour traiter les jobs en arrière-plan :\n";
    echo "   php artisan queue:work\n";
    echo "\n🗑️  Pour nettoyer la commande de test :\n";
    echo "   php artisan tinker\n";
    echo "   Order::where('customer_email', 'test@heritage-parfums.fr')->delete();\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📍 Fichier: " . $e->getFile() . " ligne " . $e->getLine() . "\n";
    exit(1);
}
