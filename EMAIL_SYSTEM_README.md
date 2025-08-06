# Système d'Emails Automatiques - Heritage Parfums

## Vue d'ensemble

Ce document décrit le système d'envoi automatique d'emails implémenté pour la boutique Heritage Parfums. Le système envoie automatiquement :

1. **Email de confirmation** quand un client valide et paie une commande
2. **Email d'expédition** quand l'administrateur marque une commande comme expédiée

## Architecture

### Composants principaux

```
Events/          → Événements déclenchés
├── OrderPaid.php      → Déclenché quand une commande est payée
└── OrderShipped.php   → Déclenché quand une commande est expédiée

Listeners/       → Écoutent les événements et lancent les jobs
├── SendOrderConfirmedNotification.php
└── SendOrderShippedNotification.php

Jobs/            → Tâches d'envoi d'emails en arrière-plan
├── SendOrderConfirmedEmail.php
└── SendOrderShippedEmail.php

Mail/            → Classes Mailable pour construire les emails
├── OrderConfirmed.php
└── OrderShipped.php

resources/views/emails/  → Templates des emails
├── layout.blade.php           → Template de base
├── order-confirmed.blade.php  → Email de confirmation
└── order-shipped.blade.php    → Email d'expédition
```

### Flux de fonctionnement

```
1. Client paie → Order::markAsPaid() → Event OrderPaid → Listener → Job → Email
2. Admin expédie → Order::markAsShipped() → Event OrderShipped → Listener → Job → Email
```

## Configuration

### 1. Configuration email (.env)

Pour le développement (emails dans les logs) :
```env
MAIL_MAILER=log
MAIL_FROM_ADDRESS="contact@heritage-parfums.fr"
MAIL_FROM_NAME="Heritage Parfums"
```

Pour la production (avec SMTP) :
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your-email@heritage-parfums.fr
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="contact@heritage-parfums.fr"
MAIL_FROM_NAME="Heritage Parfums"
```

### 2. Configuration des queues

Le système utilise les jobs en arrière-plan pour éviter les blocages.

```env
QUEUE_CONNECTION=database
```

### 3. Traitement des jobs

En production, lancez le worker de queue :
```bash
php artisan queue:work
```

Ou utilisez un superviseur comme Supervisor pour maintenir le processus actif.

## Utilisation

### Déclenchement automatique

Le système se déclenche automatiquement quand :

1. **Commande payée :** Utilisez `$order->markAsPaid()`
2. **Commande expédiée :** Utilisez `$order->markAsShipped()`

```php
// Dans votre contrôleur de paiement
$order = Order::find($orderId);
$order->markAsPaid(); // → Déclenche l'email de confirmation

// Dans votre interface d'admin
$order = Order::find($orderId);
$order->tracking_number = 'TRACK123456';
$order->markAsShipped(); // → Déclenche l'email d'expédition
```

### Envoi manuel

Pour envoyer manuellement un email :

```php
use App\Jobs\SendOrderConfirmedEmail;
use App\Jobs\SendOrderShippedEmail;

// Email de confirmation
SendOrderConfirmedEmail::dispatch($order);

// Email d'expédition  
SendOrderShippedEmail::dispatch($order);
```

## Tests

### Test automatisé

Lancez le script de test :
```bash
php test_email_system.php
```

Ce script :
- Crée une commande de test
- Déclenche les deux types d'emails
- Affiche le statut des jobs

### Test manuel avec Tinker

```bash
php artisan tinker
```

```php
// Créer une commande de test
$order = \App\Models\Order::factory()->create([
    'customer_email' => 'test@example.com'
]);

// Tester l'email de confirmation
$order->markAsPaid();

// Tester l'email d'expédition
$order->tracking_number = 'TEST123';
$order->markAsShipped();

// Voir les jobs en queue
DB::table('jobs')->count();
```

## Personnalisation

### Modifier les templates d'emails

Les templates se trouvent dans `resources/views/emails/` :

- `layout.blade.php` : Template de base (logo, styles, footer)
- `order-confirmed.blade.php` : Email de confirmation
- `order-shipped.blade.php` : Email d'expédition

### Ajouter des informations

Pour ajouter des informations aux emails, modifiez :

1. Les classes Mailable (`app/Mail/`)
2. Les templates Blade correspondants

Exemple - ajouter des informations produit :

```php
// Dans app/Mail/OrderConfirmed.php
public function build()
{
    return $this->subject('...')
                ->view('emails.order-confirmed')
                ->with([
                    'order' => $this->order,
                    'items' => $this->order->items()->with('product')->get(),
                    'customer_reviews' => $this->getCustomerReviews(), // Nouveau
                ]);
}
```

### Modifier les déclencheurs

Pour changer quand les emails sont envoyés, modifiez les méthodes dans `app/Models/Order.php` :

```php
public function markAsPaid()
{
    $this->payment_status = 'paid';
    $this->status = 'processing';
    $this->save();
    
    // Condition personnalisée
    if ($this->total_amount > 100) {
        event(new OrderPaid($this));
    }
}
```

## Monitoring

### Vérifier les logs

Les emails et erreurs sont loggés dans `storage/logs/laravel.log` :

```bash
tail -f storage/logs/laravel.log | grep -E "(Email|Mail|Job)"
```

### Surveiller les jobs échoués

```bash
php artisan queue:failed
```

Pour relancer les jobs échoués :
```bash
php artisan queue:retry all
```

### Statistiques des jobs

```bash
php artisan queue:monitor
```

## Dépannage

### Les emails ne sont pas envoyés

1. **Vérifiez la configuration :**
   ```bash
   php artisan config:cache
   php artisan config:clear
   ```

2. **Vérifiez les jobs :**
   ```bash
   php artisan queue:work --verbose
   ```

3. **Vérifiez les logs :**
   ```bash
   tail -f storage/logs/laravel.log
   ```

### Jobs bloqués

```bash
# Nettoyer les jobs échoués
php artisan queue:flush

# Redémarrer les workers
php artisan queue:restart
```

### Emails en doublon

Les événements peuvent se déclencher plusieurs fois. Pour éviter cela :

```php
// Dans le modèle Order
public function markAsPaid()
{
    if ($this->payment_status === 'paid') {
        return; // Déjà payé, ne pas redéclencher
    }
    
    $this->payment_status = 'paid';
    $this->status = 'processing';
    $this->save();
    
    event(new OrderPaid($this));
}
```

## Commandes utiles

```bash
# Voir les jobs en queue
php artisan queue:work --verbose

# Voir les jobs échoués
php artisan queue:failed

# Relancer tous les jobs échoués
php artisan queue:retry all

# Nettoyer les jobs échoués
php artisan queue:flush

# Tester la configuration email
php artisan tinker
Mail::raw('Test email', function($message) {
    $message->to('test@example.com')->subject('Test');
});
```

## Prochaines améliorations possibles

1. **Email de livraison confirmée** quand `markAsDelivered()` est appelé
2. **Emails de suivi** avec intégration APIs transporteurs
3. **Emails marketing** pour les promotions
4. **Notifications admin** pour les nouvelles commandes
5. **Templates personnalisables** via l'interface d'admin
6. **Statistiques d'ouverture** des emails
