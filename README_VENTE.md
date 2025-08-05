# Guide de Démonstration - Page de Vente Heritage Parfums

## 🎯 Objectif
Ce guide vous explique comment lancer la page de vente pour un seul produit parfum dans votre projet Laravel.

## 📋 Prérequis
- MAMP ou équivalent avec MySQL sur port 8889
- PHP 8.1+
- Composer installé

## 🚀 Démarrage Rapide

### 1. Configuration de la base de données
```bash
# Créer la base de données dans MAMP
# Nom: heritage_parfums
# Utilisateur: root
# Mot de passe: root
# Port: 8889
```

### 2. Installation des dépendances
```bash
cd /Users/halick/ProjetLaravel/HeritageParfums
composer install
npm install
```

### 3. Migration et données d'exemple
```bash
# Migrer les tables
php artisan migrate

# Ajouter les produits d'exemple (13 parfums)
php artisan db:seed --class=ProductSeeder
```

### 4. Lancement du serveur
```bash
# Terminal 1 - Serveur Laravel
php artisan serve --port=8001

# Terminal 2 - Assets (CSS/JS)
npm run dev
```

## 🎨 Pages de Vente Disponibles

Avec les données d'exemple, vous pouvez accéder à ces pages :

### Parfums Femme
- http://localhost:8001/product/eternelle-rose (Bestseller)
- http://localhost:8001/product/jasmin-etoile
- http://localhost:8001/product/pivoine-delicate  
- http://localhost:8001/product/orchidee-noire (Exclusif)

### Parfums Homme
- http://localhost:8001/product/bois-mystique
- http://localhost:8001/product/cuir-noble (Nouveau)
- http://localhost:8001/product/vetiver-sauvage
- http://localhost:8001/product/oud-royal (Exclusif)

### Collections Exclusives
- http://localhost:8001/product/ambre-precieux (Édition Limitée)
- http://localhost:8001/product/or-et-encens (Pièce d'Exception)

### Nouveautés
- http://localhost:8001/product/fleur-de-cerisier (Nouveau)
- http://localhost:8001/product/absolu-de-vanille (Nouveau)

## ✨ Fonctionnalités de la Page de Vente

### 📱 Interface Utilisateur
- **Design responsive** adapté mobile/desktop
- **Images haute qualité** avec effet hover
- **Badge produit** (Nouveau, Bestseller, Exclusif)
- **Typographie élégante** avec Playfair Display

### 🛒 Fonctionnalités E-commerce
- **Gestion du stock** en temps réel
- **Sélecteur de quantité** avec limites
- **Ajout au panier AJAX** sans rechargement
- **Notifications** de succès/erreur
- **Calcul automatique** des prix

### 🌸 Informations Parfum
- **Notes olfactives** détaillées (tête, cœur, fond)
- **Description complète** et courte
- **Informations produit** (type, taille, catégorie)
- **Services inclus** (livraison, emballage, retours)

### 🔗 Navigation
- **Produits similaires** de la même catégorie
- **Navigation fluide** entre produits
- **URLs SEO-friendly** avec slugs

## 🎯 Exemple Complet : Éternelle Rose

```
URL: http://localhost:8001/product/eternelle-rose

Caractéristiques:
- Prix: 185,00 €
- Type: Eau de Parfum 100ml
- Stock: 25 unités
- Badge: Bestseller
- Catégorie: Parfums Femme

Notes Olfactives:
- Tête: bergamote, cassis
- Cœur: rose de Damas, pivoine  
- Fond: musc blanc, bois de santal
```

## 🛠 Structure Technique

### Modèle Product
```php
// Champs principaux
- name, slug, description, short_description
- price (decimal), category, type, size
- images (JSON), notes (JSON)
- stock, is_active, is_featured, badge

// Relations et méthodes
- orderItems() relationship
- getFormattedPriceAttribute()
- getMainImageAttribute()
- isInStock(), decrementStock()
```

### Route
```php
Route::get('/product/{slug}', [ProductController::class, 'show'])
     ->name('product.show');
```

### Contrôleur
```php
public function show($slug) {
    $product = Product::where('slug', $slug)
                     ->where('is_active', true)
                     ->firstOrFail();
    
    $relatedProducts = Product::where('category', $product->category)
                             ->where('id', '!=', $product->id)
                             ->limit(4)->get();
                             
    return view('products.show', compact('product', 'relatedProducts'));
}
```

## 🎨 Personnalisation CSS

Les couleurs principales définies :
```css
:root {
    --primary-gold: #d4af37;
    --secondary-gold: #b8860b;
    --dark-gold: #9b7a0b;
    --cream: #faf9f6;
    --light-gray: #f8f9fa;
    --deep-black: #2c2c2c;
}
```

## 📱 Responsive Design

- **Desktop**: Layout 2 colonnes (image/info)
- **Mobile**: Layout empilé avec adaptations
- **Tablet**: Mise en page intermédiaire

## 🔄 Intégration Panier

Le bouton "Ajouter au Panier" utilise AJAX :
```javascript
fetch('/cart/add', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token
    },
    body: JSON.stringify({
        product_id: productId,
        quantity: quantity
    })
})
```

## 📊 Données d'Exemple

Le ProductSeeder contient :
- **4 parfums femme** (rose, jasmin, pivoine, orchidée)
- **4 parfums homme** (bois, cuir, vétiver, oud)  
- **2 exclusifs** (ambre, encens)
- **3 nouveautés** (cerisier, vanille)

## 🎯 Prochaines Étapes

Pour étendre la fonctionnalité :
1. **Panier d'achat** complet
2. **Système de paiement** (Stripe/PayPal)
3. **Gestion des commandes**
4. **Avis clients** et notes
5. **Programme de fidélité**
6. **Recommandations** basées sur l'IA

---

🌟 **Votre page de vente est maintenant prête à être utilisée !**
