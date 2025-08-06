# Guide de Déploiement des Emails sur Hostinger

## 🚀 Configuration des Emails Automatiques sur Hostinger

### Problème
Hostinger (hébergement partagé) ne permet pas d'exécuter `php artisan queue:work --daemon` en permanence.

### ✅ Solution : Cron Jobs

#### 1. Configuration du Cron Job

Dans le panneau de contrôle Hostinger (hPanel) :

1. Allez dans **Cron Jobs**
2. Créez un nouveau cron job :

```bash
# Exécute toutes les minutes
* * * * * cd /home/u123456789/public_html && php artisan queue:work --stop-when-empty --timeout=30
```

Ou plus simple :

```bash
# Traite les jobs en queue toutes les minutes
* * * * * cd /path/to/your/laravel && php artisan schedule:run
```

#### 2. Ajoutez dans app/Console/Kernel.php

```php
<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // Traite les emails en queue toutes les minutes
        $schedule->command('queue:work --stop-when-empty --timeout=30')->everyMinute();
        
        // Alternative : traite X jobs à la fois
        $schedule->command('queue:work --once --timeout=30')->everyMinute();
    }
}
```

### ✅ Option 2 : Emails Synchrones (Plus Simple)

Si les cron jobs ne fonctionnent pas, envoyez les emails directement (sans queue) :

#### Modifiez config/queue.php :

```php
'default' => env('QUEUE_CONNECTION', 'sync'),
```

#### Dans votre .env :

```env
QUEUE_CONNECTION=sync
```

#### Avantages/Inconvénients :

✅ **Avantages :**
- Fonctionne sur tous les hébergeurs
- Configuration simple
- Emails envoyés immédiatement

❌ **Inconvénients :**
- Peut ralentir la réponse du site
- Pas de retry automatique en cas d'erreur

### ✅ Option 3 : Service Externe (Professionnel)

Pour une solution robuste, utilisez un service externe :

#### Webhook + Service Queue :
1. **Laravel Vapor** (AWS)
2. **Laravel Forge** + serveur VPS
3. **Queue-as-a-Service** (IronMQ, AWS SQS)

### 📧 Configuration SMTP pour Hostinger

#### Avec Gmail :
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=mot-de-passe-application
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="contact@heritage-parfums.fr"
MAIL_FROM_NAME="Heritage Parfums"
```

#### Avec SMTP Hostinger :
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=contact@votre-domaine.com
MAIL_PASSWORD=votre-mot-de-passe-email
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="contact@votre-domaine.com"
MAIL_FROM_NAME="Heritage Parfums"
```

## 🔧 Étapes de Déploiement sur Hostinger

### 1. Upload des fichiers
- Uploadez votre projet Laravel dans `public_html`
- Configurez le `.env` avec les bonnes données

### 2. Configuration des permissions
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

### 3. Installation des dépendances (si SSH disponible)
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 4. Configuration du Cron Job
Dans hPanel → Cron Jobs :
```bash
* * * * * cd /home/u123456789/public_html && php artisan schedule:run
```

### 5. Test des emails
Utilisez votre script de test :
```bash
php test_mailtrap_emails.php
```

## 🚨 Dépannage Hostinger

### Erreur "Queue connection not found"
Dans `.env` :
```env
QUEUE_CONNECTION=sync
```

### Emails ne partent pas
1. Vérifiez les logs : `storage/logs/laravel.log`
2. Testez SMTP avec un script simple
3. Contactez le support Hostinger

### Cron Job ne fonctionne pas
1. Vérifiez le chemin complet vers PHP
2. Utilisez le chemin absolu vers votre projet
3. Ajoutez les logs : `>> /tmp/cron.log 2>&1`

## 💡 Recommandation Finale

### Pour commencer (Simple) :
```env
QUEUE_CONNECTION=sync
```
→ Emails envoyés directement, fonctionne partout

### Pour optimiser (Avancé) :
1. Configurez les Cron Jobs
2. Utilisez `QUEUE_CONNECTION=database`
3. Surveillez les performances

### Pour du professionnel :
Migrez vers un VPS avec Laravel Forge ou AWS
