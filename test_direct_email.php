<?php

/**
 * Test simple d'envoi d'email
 */

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;
use App\Models\OrderItem;
use App\Mail\OrderConfirmed;
use Illuminate\Support\Facades\Mail;

echo "🧪 Test simple d'envoi d'email\n";
echo "==============================\n\n";

try {
    // Récupérer la première commande de test
    $order = Order::with('items.product')
                  ->where('customer_email', 'test@heritage-parfums.fr')
                  ->first();
    
    if (!$order) {
        echo "❌ Aucune commande de test trouvée. Lancez d'abord test_email_system.php\n";
        exit(1);
    }
    
    echo "📦 Commande trouvée: {$order->order_number}\n";
    echo "📧 Email: {$order->customer_email}\n\n";
    
    // Envoyer l'email de confirmation directement
    echo "📨 Envoi de l'email de confirmation...\n";
    
    Mail::to($order->customer_email)
        ->send(new OrderConfirmed($order));
    
    echo "✅ Email envoyé avec succès !\n\n";
    echo "📝 Vérifiez les logs: storage/logs/laravel.log\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📍 Fichier: " . $e->getFile() . " ligne " . $e->getLine() . "\n";
    exit(1);
}
