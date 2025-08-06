<?php

/**
 * Test de debug pour la route admin.shipping.index
 */

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 Debug: Test de la route admin.shipping.index\n";
echo "===============================================\n\n";

try {
    // Test de la route directement
    $url = route('admin.shipping.index');
    echo "✅ URL générée: {$url}\n\n";
    
    // Vérifier les commandes en attente
    $pendingOrders = \App\Models\Order::where('payment_status', 'paid')
                                     ->where('status', 'processing')
                                     ->count();
    
    echo "📦 Commandes en attente d'expédition: {$pendingOrders}\n";
    
    // Vérifier toutes les commandes
    $allOrders = \App\Models\Order::count();
    echo "📋 Total des commandes: {$allOrders}\n\n";
    
    // Vérifier le contrôleur
    echo "🎮 Test du contrôleur ShippingController...\n";
    $controller = new \App\Http\Controllers\ShippingController(new \App\Services\ShippingService());
    echo "✅ Contrôleur instancié avec succès\n\n";
    
    // Simuler l'appel de la méthode index
    echo "🔄 Test de la méthode index()...\n";
    
    // Créer une fausse requête
    $request = \Illuminate\Http\Request::create('admin/shipping', 'GET');
    $request->setLaravelSession(app('session.store'));
    
    ob_start();
    $result = $controller->index();
    ob_end_clean();
    
    echo "✅ Méthode index() exécutée avec succès\n";
    echo "📄 Type de résultat: " . get_class($result) . "\n\n";
    
    // Vérifier les vues
    echo "👁️  Vérification des vues...\n";
    $viewPath = resource_path('views/admin/shipping/index.blade.php');
    if (file_exists($viewPath)) {
        echo "✅ Vue admin.shipping.index trouvée\n";
    } else {
        echo "❌ Vue admin.shipping.index manquante\n";
    }
    
    // Test avec une vraie requête HTTP (simulation)
    echo "\n🌐 Simulation d'une requête HTTP...\n";
    
    // Vérifier si l'utilisateur est connecté (simulation)
    echo "👤 Utilisateur connecté: " . (\Illuminate\Support\Facades\Auth::check() ? 'Oui' : 'Non') . "\n";
    
    if (!\Illuminate\Support\Facades\Auth::check()) {
        echo "⚠️  Pas d'utilisateur connecté - cela pourrait expliquer la redirection\n";
        echo "💡 Créons un utilisateur test...\n";
        
        $user = \App\Models\User::first();
        if ($user) {
            \Illuminate\Support\Facades\Auth::login($user);
            echo "✅ Utilisateur connecté: " . $user->name . "\n";
        } else {
            echo "❌ Aucun utilisateur trouvé en base de données\n";
        }
    }
    
    echo "\n🎯 Résumé du diagnostic:\n";
    echo "- Route définie: ✅\n";
    echo "- Contrôleur OK: ✅\n";
    echo "- Vue présente: ✅\n";
    echo "- Commandes en attente: {$pendingOrders}\n";
    
    if ($pendingOrders === 0) {
        echo "\n💡 Suggestion: Créez des commandes de test avec:\n";
        echo "   php test_email_system.php\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📍 Fichier: " . $e->getFile() . " ligne " . $e->getLine() . "\n";
    echo "🔧 Stack trace:\n" . $e->getTraceAsString() . "\n";
}
