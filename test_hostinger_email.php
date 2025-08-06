<?php
/**
 * Test Email Simple pour Hostinger
 * À utiliser une fois déployé sur Hostinger
 */

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🧪 Test Email Hostinger - Heritage Parfums\n";
echo "==========================================\n\n";

try {
    echo "Configuration actuelle :\n";
    echo "- Queue: " . config('queue.default') . "\n";
    echo "- Mailer: " . config('mail.default') . "\n";
    echo "- Host: " . config('mail.mailers.smtp.host') . "\n\n";
    
    // Test simple
    \Mail::raw(
        "Test email depuis Hostinger\nVotre système Heritage Parfums fonctionne !", 
        function($message) {
            $message->to('test@heritage-parfums.fr')
                   ->subject('✅ Test Hostinger - Heritage Parfums OK');
        }
    );
    
    echo "✅ Email de test envoyé !\n";
    echo "Vérifiez votre boîte email ou Mailtrap.\n";
    
} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
    echo "Vérifiez votre configuration SMTP dans .env\n";
}
?>