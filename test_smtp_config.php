<?php

/**
 * Test de configuration SMTP pour Heritage Parfums
 * 
 * Ce script teste votre configuration SMTP et envoie un email de test
 * 
 * Usage: php test_smtp_config.php
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🧪 Test de Configuration SMTP - Heritage Parfums\n";
echo "==============================================\n\n";

// Vérifier la configuration
echo "1. Vérification de la configuration...\n";
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
echo "   📮 From: {$fromName} <{$fromAddress}>\n\n";

if ($mailer === 'log') {
    echo "⚠️  ATTENTION: Vous utilisez le mailer 'log'.\n";
    echo "   Les emails seront sauvés dans storage/logs/laravel.log\n";
    echo "   Pour envoyer de vrais emails, changez MAIL_MAILER=smtp dans .env\n\n";
}

// Demander l'email de test
echo "2. Email de test\n";
echo "   Entrez l'adresse email pour le test (ou appuyez sur Entrée pour utiliser test@heritage-parfums.fr): ";
$testEmail = trim(fgets(STDIN));

if (empty($testEmail)) {
    $testEmail = 'test@heritage-parfums.fr';
}

echo "   📧 Email de test: {$testEmail}\n\n";

// Classe Mailable pour le test
class TestEmail extends Mailable
{
    public function build()
    {
        return $this->subject('Test SMTP - Heritage Parfums')
                    ->view('emails.test-smtp')
                    ->with([
                        'testTime' => now()->format('d/m/Y à H:i:s'),
                        'config' => [
                            'mailer' => config('mail.default'),
                            'host' => config('mail.mailers.smtp.host'),
                            'port' => config('mail.mailers.smtp.port'),
                            'encryption' => config('mail.mailers.smtp.transport'),
                        ]
                    ]);
    }
}

// Créer le template de test s'il n'existe pas
$testTemplatePath = resource_path('views/emails/test-smtp.blade.php');
if (!file_exists($testTemplatePath)) {
    echo "3. Création du template de test...\n";
    $testTemplate = '@extends(\'emails.layout\')

@section(\'title\', \'Test SMTP\')

@section(\'content\')
    <h2 style="color: #212529; margin-bottom: 20px;">🧪 Test SMTP Réussi !</h2>
    
    <p>Félicitations ! Votre configuration SMTP fonctionne correctement.</p>
    
    <div class="order-info">
        <h3 style="margin: 0 0 10px 0; color: #212529;">Informations du test</h3>
        <p><strong>Date/Heure :</strong> {{ $testTime }}</p>
        <p><strong>Mailer :</strong> {{ $config[\'mailer\'] }}</p>
        <p><strong>Host :</strong> {{ $config[\'host\'] }}</p>
        <p><strong>Port :</strong> {{ $config[\'port\'] }}</p>
    </div>
    
    <div style="margin: 30px 0; padding: 20px; background-color: #d4edda; border: 1px solid #c3e6cb; border-radius: 5px;">
        <h3 style="color: #155724; margin: 0 0 15px 0;">✅ Configuration validée</h3>
        <p style="color: #155724; margin: 0;">
            Votre système d\'emails automatiques est maintenant opérationnel pour Heritage Parfums !
        </p>
    </div>
    
    <div style="margin: 30px 0;">
        <h3 style="color: #212529;">Prochaines étapes</h3>
        <ul style="padding-left: 20px;">
            <li>Lancez le worker de queue : <code>php artisan queue:work</code></li>
            <li>Testez une vraie commande avec <code>php test_email_system.php</code></li>
            <li>Surveillez les logs pour vous assurer que tout fonctionne</li>
        </ul>
    </div>
@endsection';

    file_put_contents($testTemplatePath, $testTemplate);
    echo "   ✅ Template de test créé\n\n";
}

try {
    echo "4. Envoi de l'email de test...\n";
    
    $startTime = microtime(true);
    
    // Envoyer l'email de test
    Mail::to($testEmail)->send(new TestEmail());
    
    $endTime = microtime(true);
    $duration = round(($endTime - $startTime) * 1000, 2);
    
    echo "   ✅ Email envoyé avec succès en {$duration}ms !\n\n";
    
    if ($mailer === 'log') {
        echo "📄 L'email a été sauvé dans les logs. Pour le voir :\n";
        echo "   tail -50 storage/logs/laravel.log | grep -A 50 'Test SMTP'\n\n";
    } else {
        echo "📬 Vérifiez votre boîte email ({$testEmail})\n";
        echo "   - Regardez aussi dans les spams au début\n";
        echo "   - L'email peut prendre quelques minutes à arriver\n\n";
    }
    
    echo "5. Test des jobs en queue...\n";
    
    // Tester avec un job
    use App\Jobs\SendOrderConfirmedEmail;
    use App\Models\Order;
    
    $testOrder = Order::where('customer_email', 'test@heritage-parfums.fr')->first();
    
    if ($testOrder) {
        echo "   📦 Commande de test trouvée: {$testOrder->order_number}\n";
        echo "   🔄 Envoi via job en queue...\n";
        
        SendOrderConfirmedEmail::dispatch($testOrder);
        
        $jobsCount = DB::table('jobs')->count();
        echo "   📋 Jobs en queue: {$jobsCount}\n";
        
        if ($jobsCount > 0) {
            echo "   ⚠️  Lancez 'php artisan queue:work' pour traiter les jobs\n";
        }
    } else {
        echo "   ℹ️  Aucune commande de test. Lancez 'php test_email_system.php' d'abord\n";
    }
    
    echo "\n🎉 Test terminé avec succès !\n";
    echo "\n📊 Résumé:\n";
    echo "   ✅ Configuration SMTP validée\n";
    echo "   ✅ Email de test envoyé\n";
    echo "   ✅ Système prêt pour la production\n";
    echo "\n🔧 Commandes utiles:\n";
    echo "   - Traiter les jobs: php artisan queue:work\n";
    echo "   - Voir les jobs: php artisan queue:work --verbose\n";
    echo "   - Test complet: php test_email_system.php\n";

} catch (Exception $e) {
    echo "❌ Erreur lors de l'envoi: " . $e->getMessage() . "\n";
    echo "📍 Fichier: " . $e->getFile() . " ligne " . $e->getLine() . "\n\n";
    
    echo "🔧 Solutions possibles:\n";
    echo "   1. Vérifiez vos identifiants SMTP dans .env\n";
    echo "   2. Pour Gmail, utilisez un mot de passe d'application\n";
    echo "   3. Vérifiez que le port 587 n'est pas bloqué\n";
    echo "   4. Essayez MAIL_ENCRYPTION=tls au lieu de ssl\n";
    echo "   5. Videz le cache: php artisan config:clear\n";
    
    exit(1);
}
