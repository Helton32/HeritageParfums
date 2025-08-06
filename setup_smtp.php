<?php

/**
 * Assistant de configuration SMTP interactif
 * 
 * Guide l'utilisateur pour configurer son SMTP étape par étape
 * 
 * Usage: php setup_smtp.php
 */

require_once __DIR__ . '/vendor/autoload.php';

echo "🚀 Assistant de Configuration SMTP - Heritage Parfums\n";
echo "====================================================\n\n";

echo "Cet assistant vous aide à configurer l'envoi d'emails pour votre boutique.\n";
echo "Vos emails de commandes seront envoyés automatiquement aux clients !\n\n";

// Fonction pour lire une entrée utilisateur
function readline_custom($prompt) {
    echo $prompt;
    return trim(fgets(STDIN));
}

// Fonction pour créer le fichier .env
function updateEnvFile($config) {
    $envPath = __DIR__ . '/.env';
    $envContent = file_get_contents($envPath);
    
    // Remplacer les valeurs MAIL
    $replacements = [
        '/MAIL_MAILER=.*/m' => "MAIL_MAILER={$config['mailer']}",
        '/MAIL_HOST=.*/m' => "MAIL_HOST={$config['host']}",
        '/MAIL_PORT=.*/m' => "MAIL_PORT={$config['port']}",
        '/MAIL_USERNAME=.*/m' => "MAIL_USERNAME={$config['username']}",
        '/MAIL_PASSWORD=.*/m' => "MAIL_PASSWORD={$config['password']}",
        '/MAIL_ENCRYPTION=.*/m' => "MAIL_ENCRYPTION={$config['encryption']}",
        '/MAIL_FROM_ADDRESS=.*/m' => "MAIL_FROM_ADDRESS=\"{$config['from_address']}\"",
        '/MAIL_FROM_NAME=.*/m' => "MAIL_FROM_NAME=\"{$config['from_name']}\"",
    ];
    
    foreach ($replacements as $pattern => $replacement) {
        $envContent = preg_replace($pattern, $replacement, $envContent);
    }
    
    file_put_contents($envPath, $envContent);
}

echo "👋 Choisissez votre fournisseur SMTP :\n\n";
echo "1. 🟢 Brevo (ex-Sendinblue) - RECOMMANDÉ\n";
echo "   • 300 emails/jour gratuits\n";
echo "   • Parfait pour les boutiques\n";
echo "   • Configuration simple\n\n";

echo "2. 📧 Gmail\n";
echo "   • 500 emails/jour gratuits\n";
echo "   • Nécessite un mot de passe d'application\n";
echo "   • Peut être limité par Google\n\n";

echo "3. 🔷 Outlook/Hotmail\n";
echo "   • 300 emails/jour gratuits\n";
echo "   • Configuration simple\n\n";

echo "4. 🧪 Mailtrap (Tests uniquement)\n";
echo "   • Pour développement/tests\n";
echo "   • Les emails ne sont pas vraiment envoyés\n\n";

echo "5. ⚙️  Configuration manuelle\n";
echo "   • Pour les utilisateurs avancés\n\n";

$choice = readline_custom("Votre choix (1-5) : ");

switch ($choice) {
    case '1':
        echo "\n🟢 Configuration Brevo\n";
        echo "======================\n\n";
        
        echo "1. Allez sur https://brevo.com et créez un compte gratuit\n";
        echo "2. Confirmez votre email\n";
        echo "3. Allez dans 'SMTP & API' → 'SMTP'\n";
        echo "4. Notez vos identifiants SMTP\n\n";
        
        $email = readline_custom("📧 Votre email Brevo (login) : ");
        $password = readline_custom("🔑 Votre clé SMTP Brevo : ");
        $fromAddress = readline_custom("📮 Email d'expédition (ex: contact@heritage-parfums.fr) : ");
        
        $config = [
            'mailer' => 'smtp',
            'host' => 'smtp-relay.brevo.com',
            'port' => '587',
            'username' => $email,
            'password' => $password,
            'encryption' => 'tls',
            'from_address' => $fromAddress ?: 'contact@heritage-parfums.fr',
            'from_name' => 'Heritage Parfums'
        ];
        break;
        
    case '2':
        echo "\n📧 Configuration Gmail\n";
        echo "======================\n\n";
        
        echo "⚠️  IMPORTANT: Vous devez créer un mot de passe d'application !\n\n";
        echo "1. Allez dans votre compte Google → Sécurité\n";
        echo "2. Activez l'authentification à 2 facteurs\n";
        echo "3. Allez dans 'Mots de passe d'application'\n";
        echo "4. Créez un mot de passe pour 'Heritage Parfums'\n";
        echo "5. Copiez le mot de passe de 16 caractères\n\n";
        
        $email = readline_custom("📧 Votre email Gmail : ");
        $password = readline_custom("🔑 Mot de passe d'application (16 caractères) : ");
        
        $config = [
            'mailer' => 'smtp',
            'host' => 'smtp.gmail.com',
            'port' => '587',
            'username' => $email,
            'password' => $password,
            'encryption' => 'tls',
            'from_address' => $email,
            'from_name' => 'Heritage Parfums'
        ];
        break;
        
    case '3':
        echo "\n🔷 Configuration Outlook\n";
        echo "========================\n\n";
        
        $email = readline_custom("📧 Votre email Outlook/Hotmail : ");
        $password = readline_custom("🔑 Votre mot de passe : ");
        
        $config = [
            'mailer' => 'smtp',
            'host' => 'smtp-mail.outlook.com',
            'port' => '587',
            'username' => $email,
            'password' => $password,
            'encryption' => 'starttls',
            'from_address' => $email,
            'from_name' => 'Heritage Parfums'
        ];
        break;
        
    case '4':
        echo "\n🧪 Configuration Mailtrap\n";
        echo "=========================\n\n";
        
        echo "1. Allez sur https://mailtrap.io et créez un compte\n";
        echo "2. Créez une nouvelle 'Inbox'\n";
        echo "3. Cliquez sur 'Show Credentials'\n\n";
        
        $username = readline_custom("👤 Username Mailtrap : ");
        $password = readline_custom("🔑 Password Mailtrap : ");
        
        $config = [
            'mailer' => 'smtp',
            'host' => 'sandbox.smtp.mailtrap.io',
            'port' => '2525',
            'username' => $username,
            'password' => $password,
            'encryption' => 'tls',
            'from_address' => 'contact@heritage-parfums.fr',
            'from_name' => 'Heritage Parfums'
        ];
        break;
        
    case '5':
        echo "\n⚙️  Configuration manuelle\n";
        echo "==========================\n\n";
        
        $host = readline_custom("🌐 Host SMTP : ");
        $port = readline_custom("🔌 Port (587 ou 465) : ");
        $username = readline_custom("👤 Username : ");
        $password = readline_custom("🔑 Password : ");
        $encryption = readline_custom("🔐 Encryption (tls/ssl/starttls) : ");
        $fromAddress = readline_custom("📮 From Address : ");
        
        $config = [
            'mailer' => 'smtp',
            'host' => $host,
            'port' => $port ?: '587',
            'username' => $username,
            'password' => $password,
            'encryption' => $encryption ?: 'tls',
            'from_address' => $fromAddress,
            'from_name' => 'Heritage Parfums'
        ];
        break;
        
    default:
        echo "❌ Choix invalide. Relancez le script.\n";
        exit(1);
}

// Sauvegarder la configuration
echo "\n💾 Sauvegarde de la configuration...\n";

// Backup du .env actuel
copy('.env', '.env.backup.' . date('Y-m-d-H-i-s'));
echo "   ✅ Backup créé : .env.backup." . date('Y-m-d-H-i-s') . "\n";

// Mettre à jour le .env
updateEnvFile($config);
echo "   ✅ Fichier .env mis à jour\n";

// Vider le cache Laravel
echo "\n🧹 Nettoyage du cache Laravel...\n";
exec('php artisan config:clear 2>&1', $output, $return);
if ($return === 0) {
    echo "   ✅ Cache config vidé\n";
} else {
    echo "   ⚠️  Erreur lors du vidage de cache\n";
}

echo "\n📋 Configuration appliquée :\n";
echo "   📧 Mailer: {$config['mailer']}\n";
echo "   🌐 Host: {$config['host']}\n";
echo "   🔌 Port: {$config['port']}\n";
echo "   👤 Username: {$config['username']}\n";
echo "   📮 From: {$config['from_address']}\n\n";

// Test automatique
echo "🧪 Voulez-vous tester la configuration maintenant ? (o/N) : ";
$testNow = trim(fgets(STDIN));

if (strtolower($testNow) === 'o' || strtolower($testNow) === 'oui') {
    echo "\n🚀 Lancement du test...\n";
    echo "=====================================\n\n";
    
    // Lancer le test SMTP
    passthru('php test_smtp_config.php');
} else {
    echo "\n✅ Configuration terminée !\n\n";
    echo "🔧 Prochaines étapes :\n";
    echo "1. Testez votre configuration : php test_smtp_config.php\n";
    echo "2. Lancez le worker de queue : php artisan queue:work\n";
    echo "3. Testez le système complet : php test_email_system.php\n\n";
    
    echo "📚 Documentation complète dans SMTP_SETUP_GUIDE.md\n";
}
