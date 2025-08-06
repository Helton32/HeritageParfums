<?php

/**
 * Configuration rapide Mailtrap pour tests
 * Permet de tester immédiatement le système d'emails
 */

require_once __DIR__ . '/vendor/autoload.php';

echo "⚡ Configuration Rapide Mailtrap - Heritage Parfums\n";
echo "==================================================\n\n";

echo "🎯 Cette configuration vous permet de tester immédiatement votre système d'emails !\n";
echo "   Les emails seront capturés par Mailtrap (pas envoyés aux vrais clients).\n\n";

echo "📝 Instructions :\n";
echo "1. Allez sur https://mailtrap.io\n";
echo "2. Créez un compte gratuit (avec GitHub/Google, c'est rapide)\n";
echo "3. Une fois connecté, vous verrez une inbox 'My Inbox'\n";
echo "4. Cliquez sur 'Show Credentials' ou l'engrenage\n";
echo "5. Copiez le Username et Password\n\n";

echo "Exemple de ce que vous devriez voir :\n";
echo "┌─────────────────────────────────┐\n";
echo "│ Host: sandbox.smtp.mailtrap.io  │\n";
echo "│ Port: 2525                      │\n";
echo "│ Username: 1a2b3c4d5e6f7g        │\n";
echo "│ Password: 9h8i7j6k5l4m3n        │\n";
echo "│ Auth: Plain                     │\n";
echo "│ TLS: Optional                   │\n";
echo "└─────────────────────────────────┘\n\n";

// Fonction pour mettre à jour le .env
function updateEnvQuick($username, $password) {
    $envPath = __DIR__ . '/.env';
    $envContent = file_get_contents($envPath);
    
    $replacements = [
        '/MAIL_MAILER=.*/m' => 'MAIL_MAILER=smtp',
        '/MAIL_HOST=.*/m' => 'MAIL_HOST=sandbox.smtp.mailtrap.io',
        '/MAIL_PORT=.*/m' => 'MAIL_PORT=2525',
        '/MAIL_USERNAME=.*/m' => "MAIL_USERNAME={$username}",
        '/MAIL_PASSWORD=.*/m' => "MAIL_PASSWORD={$password}",
        '/MAIL_ENCRYPTION=.*/m' => 'MAIL_ENCRYPTION=tls',
        '/MAIL_FROM_ADDRESS=.*/m' => 'MAIL_FROM_ADDRESS="contact@heritage-parfums.fr"',
        '/MAIL_FROM_NAME=.*/m' => 'MAIL_FROM_NAME="Heritage Parfums"',
    ];
    
    foreach ($replacements as $pattern => $replacement) {
        $envContent = preg_replace($pattern, $replacement, $envContent);
    }
    
    // Backup avant modification
    copy($envPath, $envPath . '.backup.' . date('Y-m-d-H-i-s'));
    file_put_contents($envPath, $envContent);
}

// Saisie des identifiants
echo "Entrez vos identifiants Mailtrap :\n";
echo "Username : ";
$username = trim(fgets(STDIN));

echo "Password : ";
$password = trim(fgets(STDIN));

if (empty($username) || empty($password)) {
    echo "❌ Username et Password requis !\n";
    exit(1);
}

echo "\n💾 Configuration en cours...\n";

// Mise à jour du .env
updateEnvQuick($username, $password);
echo "   ✅ .env mis à jour\n";

// Vider le cache
exec('php artisan config:clear 2>&1', $output, $return);
if ($return === 0) {
    echo "   ✅ Cache vidé\n";
} else {
    echo "   ⚠️  Attention: videz le cache manuellement avec 'php artisan config:clear'\n";
}

echo "\n🎉 Configuration terminée !\n\n";

echo "📋 Configuration appliquée :\n";
echo "   📧 Service: Mailtrap (test)\n";
echo "   🌐 Host: sandbox.smtp.mailtrap.io\n";
echo "   🔌 Port: 2525\n";
echo "   👤 Username: {$username}\n";
echo "   📮 From: Heritage Parfums <contact@heritage-parfums.fr>\n\n";

echo "🚀 Test automatique en cours...\n";
echo "=================================\n\n";

// Bootstrap Laravel pour le test
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // Test simple
    use Illuminate\Support\Facades\Mail;
    
    Mail::raw('🎉 Test Mailtrap réussi ! Votre configuration Heritage Parfums fonctionne.', function($message) {
        $message->to('test@heritage-parfums.fr')
                ->subject('✅ Test SMTP Heritage Parfums - Configuration OK');
    });
    
    echo "✅ Email de test envoyé avec succès !\n\n";
    
    echo "🔍 Pour voir l'email :\n";
    echo "1. Retournez sur mailtrap.io\n";
    echo "2. Cliquez sur votre inbox 'My Inbox'\n";
    echo "3. Vous devriez voir votre email de test !\n\n";
    
    echo "🧪 Test complet du système :\n";
    echo "Voulez-vous tester une vraie commande maintenant ? (o/N) : ";
    $testFull = trim(fgets(STDIN));
    
    if (strtolower($testFull) === 'o' || strtolower($testFull) === 'oui') {
        echo "\n🚀 Test complet du système d'emails...\n";
        echo "=====================================\n";
        passthru('php test_email_system.php');
        
        echo "\n🔄 Traitement des jobs...\n";
        echo "=========================\n";
        echo "Les emails sont maintenant dans la queue. Traitement en cours...\n\n";
        
        // Traiter quelques jobs
        for ($i = 0; $i < 4; $i++) {
            echo "Traitement du job " . ($i + 1) . "...\n";
            exec('php artisan queue:work --once --quiet 2>&1', $output, $return);
            if ($return !== 0) {
                break;
            }
            usleep(500000); // 0.5 seconde
        }
        
        echo "\n🎯 Vérifiez votre inbox Mailtrap :\n";
        echo "   → Vous devriez voir 2-3 emails :\n";
        echo "     • Email de confirmation de commande\n";
        echo "     • Email d'expédition\n";
        echo "     • Email de test\n\n";
        
    } else {
        echo "\n📚 Pour tester plus tard :\n";
        echo "   php test_email_system.php     # Créer une commande test\n";
        echo "   php artisan queue:work        # Traiter les emails\n\n";
    }
    
    echo "🎉 Votre système d'emails Heritage Parfums est opérationnel !\n\n";
    
    echo "🔄 Prochaines étapes :\n";
    echo "1. Testez différentes commandes avec test_email_system.php\n";
    echo "2. Quand vous êtes prêt pour la production, reconfigurez avec :\n";
    echo "   • php setup_smtp.php (assistant interactif)\n";
    echo "   • Ou modifiez directement .env avec Brevo/Gmail\n";
    echo "3. En production, lancez : php artisan queue:work\n\n";
    
} catch (Exception $e) {
    echo "❌ Erreur de test : " . $e->getMessage() . "\n\n";
    echo "🔧 Vérifications :\n";
    echo "1. Username/Password Mailtrap corrects ?\n";
    echo "2. Connexion Internet OK ?\n";
    echo "3. Essayez : php artisan config:clear\n";
    echo "4. Puis relancez : php test_smtp_config.php\n";
}
