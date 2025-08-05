# 🚛 Système de Bons de Livraison - Héritage Parfums

## Vue d'ensemble

Ce système génère automatiquement des bons de livraison professionnels pour les principaux transporteurs français :
- **Colissimo** (La Poste)
- **Chronopost** (livraison express)
- **Mondial Relay** (réseau de points relais)

## ✨ Fonctionnalités

### 🎯 Génération Automatique
- Bons de livraison PDF avec mise en page professionnelle
- Codes-barres et numéros de suivi automatiques
- Calcul automatique du poids et des dimensions
- Templates personnalisés par transporteur

### 📊 Interface d'Administration
- Gestion centralisée des expéditions
- Assignation des transporteurs
- Suivi des statuts de livraison
- Statistiques détaillées et graphiques

### 🔧 API et Intégration
- Endpoints AJAX pour la sélection dynamique
- Recherche de points relais Mondial Relay
- Calcul automatique des tarifs
- Support multi-zones (France, Europe, International)

## 🏗️ Architecture Technique

### Modèles
```
Order.php              - Commandes étendues avec champs expédition
OrderItem.php          - Articles de commande
ShippingCarrier.php    - Configuration des transporteurs
```

### Services
```
ShippingService.php    - Logique métier principale
```

### Controllers
```
ShippingController.php - Interface d'administration
PaymentController.php  - Intégration checkout (étendu)
```

### Templates Blade
```
shipping/labels/colissimo.blade.php    - Bon Colissimo
shipping/labels/chronopost.blade.php   - Bon Chronopost  
shipping/labels/mondialrelay.blade.php - Bon Mondial Relay
admin/shipping/index.blade.php         - Liste des expéditions
admin/shipping/show.blade.php          - Détail expédition
admin/shipping/statistics.blade.php    - Statistiques
```

## 🚀 Installation et Configuration

### 1. Migrations
```bash
php artisan migrate
```

### 2. Seeders
```bash
php artisan db:seed --class=ShippingCarrierSeeder
```

### 3. Stockage
```bash
php artisan storage:link
```

### 4. Test du Système
```bash
php artisan shipping:test-labels
```

## 📋 Utilisation

### Interface d'Administration
- **Liste des expéditions :** `/admin/shipping`
- **Statistiques :** `/admin/shipping/statistics`
- **Démonstration :** `/demo/shipping`

### Workflow Standard
1. **Commande payée** → Statut "processing"
2. **Assignation transporteur** → Choix du service
3. **Génération du bon** → PDF avec code-barres
4. **Numéro de suivi** → Génération automatique
5. **Expédition** → Marquage "shipped"

### Utilisation Programmatique
```php
use App\Services\ShippingService;

$shippingService = new ShippingService();

// Obtenir les transporteurs disponibles
$carriers = $shippingService->getAvailableCarriers($order);

// Créer un bon de livraison
$labelPath = $shippingService->createShippingLabel($order);

// Générer un numéro de suivi
$tracking = $shippingService->generateTrackingNumber($order);
```

## 🎨 Personnalisation

### Templates des Bons
Les templates sont entièrement personnalisables dans `resources/views/shipping/labels/`.

Chaque transporteur a son propre design :
- **Colissimo :** Couleur jaune/or, codes service DOM/DOMR
- **Chronopost :** Couleur rouge, urgence visuelle, délais express
- **Mondial Relay :** Couleur verte, informations point relais

### Configuration des Transporteurs
```php
// Dans le ShippingCarrierSeeder
'pricing' => [
    'france' => [
        'standard' => [
            ['max_weight' => 0.5, 'price' => 4.95],
            ['max_weight' => 1.0, 'price' => 6.90],
            // ...
        ]
    ]
]
```

## 🔧 API Endpoints

### Méthodes de Transport
```
GET /api/shipping/carrier-methods
?carrier=colissimo&weight=0.5&zone=france
```

### Points Relais
```
GET /api/shipping/relay-points
?postal_code=75001&city=Paris
```

## 📊 Base de Données

### Nouveaux Champs Orders
```sql
shipping_carrier         ENUM('colissimo', 'chronopost', 'mondialrelay')
shipping_method         VARCHAR(255)
tracking_number         VARCHAR(255)
carrier_reference       VARCHAR(255)
carrier_options         JSON
carrier_response        JSON
shipping_label_path     VARCHAR(255)
shipping_weight         DECIMAL(8,3)
package_dimensions      JSON
```

### Table shipping_carriers
```sql
code            VARCHAR(255) UNIQUE
name            VARCHAR(255)
methods         JSON
pricing         JSON
zones           JSON
api_config      JSON
active          BOOLEAN
sort_order      INTEGER
```

## 🧪 Tests et Démonstration

### Commande de Test
```bash
php artisan shipping:test-labels
```
Génère 3 commandes d'exemple avec bons de livraison.

### Démonstration Web
Accédez à `/demo/shipping` pour une présentation interactive.

## 📈 Statistiques Incluses

- Commandes en attente d'expédition
- Expéditions du jour/semaine
- Répartition par transporteur
- Graphiques interactifs (Chart.js)
- Export CSV

## 🔐 Sécurité

- Validation des données d'entrée
- Sanitisation des chemins de fichiers
- Contrôle d'accès par rôles (prêt pour authentification)
- Logs d'audit des expéditions

## 📦 Dépendances

- **Laravel 11** - Framework principal
- **DomPDF** - Génération des PDFs
- **Chart.js** - Graphiques statistiques
- **Bootstrap 5** - Interface utilisateur
- **Font Awesome** - Icônes

## 🎯 Extensions Possibles

### Intégrations API Réelles
- API Colissimo Web Services
- API Chronopost Shipping
- API Mondial Relay Web Services

### Fonctionnalités Avancées
- Notifications SMS/email clients
- Tracking en temps réel
- Retours et remboursements
- Gestion multi-entrepôts
- Import/export commandes

### Optimisations
- Cache des tarifs transporteurs
- Génération asynchrone des bons
- Impression en lot
- Archive automatique

## 🆘 Support et Maintenance

### Logs
Les erreurs sont loggées dans `storage/logs/laravel.log`

### Debugging
```php
// Activer le debug des bons
$order->update(['notes' => 'Debug: ' . json_encode($debugData)]);
```

### Performance
- Les PDFs sont stockés en cache
- Les requêtes sont optimisées avec Eloquent
- Les images sont optimisées pour le web

---

## 🚀 Mise en Production

1. **Variables d'environnement** pour les APIs transporteurs
2. **Backup** régulier des bons générés
3. **Monitoring** des générations de bons
4. **Authentification** pour l'interface admin

---

*Développé pour Héritage Parfums - Système de gestion d'expéditions professionnel*
