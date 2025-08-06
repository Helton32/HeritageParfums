<?php

/**
 * Configuration Automatique pour Hostinger
 * Adapte automatiquement le projet pour un hébergement partagé
 */

require_once __DIR__ . '/vendor/autoload.php';

echo "🚀 Configuration Heritage Parfums pour Hostinger\n";
echo "===============================================\n\n";

try {
    // 1. Backup du .env actuel
    echo "1. 💾 Sauvegarde du .env actuel...\n";
    
    if (file_exists('.env')) {
        copy('.env', '.env.hostinger.backup.' . date('Y-m-d-H-i-s'));
        echo "   ✅ Backup créé\n\n";
    }
    
    // 2. Configuration pour Hostinger
    echo "2. 🔧 Configuration pour hébergement partagé...\n";
    
    $envPath = __DIR__ . '/.env';
    $envContent = file_get_contents($envPath);
    
    // Modifications nécessaires pour Hostinger
    $hostingerConfig = [
        // Queue en mode synchrone (pas de daemon possible)
        '/QUEUE_CONNECTION=.*/m' => 'QUEUE_CONNECTION=sync',
        
        // Cache optimisé pour hébergement partagé
        '/CACHE_STORE=.*/m' => 'CACHE_STORE=file',
        
        // Sessions en fichier (plus compatible)
        '/SESSION_DRIVER=.*/m' => 'SESSION_DRIVER=file',
        
        // Log optimisé
        '/LOG_CHANNEL=.*/m' => 'LOG_CHANNEL=single',
        
        // Configuration SMTP par défaut (à adapter)
        '/MAIL_MAILER=.*/m' => 'MAIL_MAILER=smtp',
    ];
    
    foreach ($hostingerConfig as $pattern => $replacement) {
        if (preg_match($pattern, $envContent)) {
            $envContent = preg_replace($pattern, $replacement, $envContent);
        } else {
            // Ajouter la ligne si elle n'existe pas
            $envContent .= "\n" . $replacement . "\n";
        }
    }
    
    // Ajouter des configurations spécifiques Hostinger
    $hostingerSpecific = "
# ==========================================
# CONFIGURATION HOSTINGER - Heritage Parfums
# ==========================================

# Queue en mode synchrone (emails envoyés directement)
QUEUE_CONNECTION=sync

# Cache et sessions optimisés pour hébergement partagé
CACHE_STORE=file
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Logs simplifiés
LOG_CHANNEL=single
LOG_LEVEL=error

# SMTP - À CONFIGURER SELON VOTRE CHOIX :

# Option 1: Gmail (Recommandé pour commencer)
# MAIL_MAILER=smtp
# MAIL_HOST=smtp.gmail.com
# MAIL_PORT=587
# MAIL_USERNAME=votre-email@gmail.com
# MAIL_PASSWORD=mot-de-passe-application-google
# MAIL_ENCRYPTION=tls

# Option 2: SMTP Hostinger (Si vous avez un email personnalisé)
# MAIL_MAILER=smtp
# MAIL_HOST=smtp.hostinger.com
# MAIL_PORT=587
# MAIL_USERNAME=contact@votre-domaine.com
# MAIL_PASSWORD=mot-de-passe-email
# MAIL_ENCRYPTION=tls

# Toujours utiliser votre domaine dans FROM
MAIL_FROM_ADDRESS=\"contact@heritage-parfums.fr\"
MAIL_FROM_NAME=\"Heritage Parfums\"

# Optimisations Hostinger
APP_DEBUG=false
APP_ENV=production
";
    
    // Ajouter les configurations spécifiques
    if (!str_contains($envContent, 'CONFIGURATION HOSTINGER')) {
        $envContent .= $hostingerSpecific;
    }
    
    file_put_contents($envPath, $envContent);
    echo "   ✅ .env configuré pour Hostinger\n\n";
    
    // 3. Modification du modèle Order pour compatibilité Hostinger
    echo "3. 🔄 Adaptation du modèle Order...\n";
    
    $orderModelPath = __DIR__ . '/app/Models/Order.php';
    $orderContent = file_get_contents($orderModelPath);
    
    // Ajouter une méthode pour forcer l'envoi direct sur Hostinger
    $hostingerMethod = '
    /**
     * Méthode spécifique pour Hostinger
     * Force l\'envoi direct des emails si les queues ne fonctionnent pas
     */
    public function markAsShippedHostinger()
    {
        $this->status = \'shipped\';
        $this->shipped_at = now();
        $this->save();
        
        // Sur Hostinger, envoyer directement l\'email
        try {
            \Mail::to($this->customer_email)
                ->send(new \App\Mail\OrderShipped($this));
        } catch (\Exception $e) {
            \Log::error(\'Erreur envoi email expédition: \' . $e->getMessage());
        }
        
        // Déclencher aussi l\'événement normal
        event(new \App\Events\OrderShipped($this));
    }
';
    
    if (!str_contains($orderContent, 'markAsShippedHostinger')) {
        $orderContent = str_replace(
            '    public function getCarrierNameAttribute()',
            $hostingerMethod . "\n    public function getCarrierNameAttribute()",
            $orderContent
        );
        file_put_contents($orderModelPath, $orderContent);
    }
    
    echo "   ✅ Modèle Order adapté\n\n";
    
    // 4. Création d'un fichier de configuration Hostinger
    echo "4. 📝 Création du guide de déploiement...\n";
    
    $deployGuide = '# 🚀 Guide de Déploiement Hostinger - Heritage Parfums

## 📋 Checklist de Déploiement

### Avant upload :
- ✅ .env configuré pour Hostinger
- ✅ QUEUE_CONNECTION=sync activé
- ✅ Configuration SMTP choisie

### Sur Hostinger :
1. **Upload des fichiers** dans public_html/
2. **Permissions** : chmod 755 storage/ bootstrap/cache/
3. **Configuration email** selon votre choix
4. **Test** avec test_mailtrap_emails.php (si Mailtrap configuré)

### ⚡ Configuration SMTP Recommandée :

#### Gmail (Simple) :
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=mot-de-passe-application
MAIL_ENCRYPTION=tls
```

#### SMTP Hostinger (Professionnel) :
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=contact@votre-domaine.com
MAIL_PASSWORD=mot-de-passe
MAIL_ENCRYPTION=tls
```

## 🎯 Workflow Automatique

### Mode SYNC (Activé) :
1. Client paie → Email immédiat ✅
2. Admin expédie → Email immédiat ✅

**Avantage** : Fonctionne sur tous les hébergeurs
**Inconvénient** : Peut ralentir légèrement la réponse

## 🔧 Test après déploiement :

```bash
# Sur votre serveur Hostinger (si SSH disponible)
php artisan config:cache
php test_mailtrap_emails.php
```

## 📞 Support

Si problème d\'emails :
1. Vérifiez storage/logs/laravel.log
2. Testez SMTP avec un script simple  
3. Contactez support Hostinger

Votre système Heritage Parfums est prêt pour Hostinger ! 🎉
';
    
    file_put_contents('HOSTINGER_DEPLOYMENT.md', $deployGuide);
    echo "   ✅ Guide créé : HOSTINGER_DEPLOYMENT.md\n\n";
    
    // 5. Script de test pour Hostinger
    echo "5. 🧪 Création du script de test Hostinger...\n";
    
    $testScript = '<?php
/**
 * Test Email Simple pour Hostinger
 * À utiliser une fois déployé sur Hostinger
 */

require_once __DIR__ . \'/vendor/autoload.php\';

$app = require_once __DIR__ . \'/bootstrap/app.php\';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🧪 Test Email Hostinger - Heritage Parfums\n";
echo "==========================================\n\n";

try {
    echo "Configuration actuelle :\n";
    echo "- Queue: " . config(\'queue.default\') . "\n";
    echo "- Mailer: " . config(\'mail.default\') . "\n";
    echo "- Host: " . config(\'mail.mailers.smtp.host\') . "\n\n";
    
    // Test simple
    \Mail::raw(
        "Test email depuis Hostinger\nVotre système Heritage Parfums fonctionne !", 
        function($message) {
            $message->to(\'test@heritage-parfums.fr\')
                   ->subject(\'✅ Test Hostinger - Heritage Parfums OK\');
        }
    );
    
    echo "✅ Email de test envoyé !\n";
    echo "Vérifiez votre boîte email ou Mailtrap.\n";
    
} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
    echo "Vérifiez votre configuration SMTP dans .env\n";
}
?>';
    
    file_put_contents('test_hostinger_email.php', $testScript);
    echo "   ✅ Script de test créé : test_hostinger_email.php\n\n";
    
    // 6. Résumé
    echo "🎉 CONFIGURATION HOSTINGER TERMINÉE\n";
    echo "===================================\n";
    echo "✅ .env adapté pour hébergement partagé\n";
    echo "✅ QUEUE_CONNECTION=sync (emails directs)\n";
    echo "✅ Modèle Order optimisé\n";
    echo "✅ Guide de déploiement créé\n";
    echo "✅ Script de test disponible\n\n";
    
    echo "📋 PROCHAINES ÉTAPES :\n";
    echo "1. Configurez votre SMTP dans .env (Gmail ou Hostinger)\n";
    echo "2. Uploadez votre projet sur Hostinger\n";
    echo "3. Testez avec : php test_hostinger_email.php\n";
    echo "4. Utilisez votre interface d\'admin normalement\n\n";
    
    echo "🔥 IMPORTANT : Avec QUEUE_CONNECTION=sync\n";
    echo "→ Les emails sont envoyés IMMÉDIATEMENT\n";
    echo "→ Pas besoin de php artisan queue:work\n";
    echo "→ Fonctionne sur Hostinger ! 🎯\n";
    
} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
}
