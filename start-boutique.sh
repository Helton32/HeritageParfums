#!/bin/bash

# 🌸 Heritage Parfums - Script de Démarrage Rapide
# Ce script lance automatiquement votre boutique de parfums

echo "🌸 === HERITAGE PARFUMS - LANCEMENT DE LA BOUTIQUE ==="
echo ""

# Vérification du répertoire
if [ ! -f "artisan" ]; then
    echo "❌ Erreur: Ce script doit être exécuté depuis le répertoire du projet Laravel"
    exit 1
fi

# Installation des dépendances si nécessaire
if [ ! -d "vendor" ]; then
    echo "📦 Installation des dépendances Composer..."
    composer install
fi

if [ ! -d "node_modules" ]; then
    echo "📦 Installation des dépendances NPM..."
    npm install
fi

# Configuration de la base de données
echo "🗄️  Configuration de la base de données..."
php artisan migrate --force

# Ajout des produits d'exemple
echo "🌸 Ajout des parfums d'exemple..."
php artisan db:seed --class=ProductSeeder --force

# Génération de la clé d'application si nécessaire
php artisan key:generate

# Optimisation pour le développement
echo "⚡ Optimisation des performances..."
php artisan config:cache
php artisan route:cache

echo ""
echo "✅ === BOUTIQUE PRÊTE ! ==="
echo ""
echo "🚀 Pour lancer le serveur :"
echo "   php artisan serve --port=8001"
echo ""
echo "🎨 Pour les assets (dans un autre terminal) :"
echo "   npm run dev"
echo ""
echo "🌸 Pages de vente disponibles :"
echo "   • http://localhost:8001/product/eternelle-rose (Bestseller)"
echo "   • http://localhost:8001/product/orchidee-noire (Exclusif)"
echo "   • http://localhost:8001/product/bois-mystique (Homme)"
echo "   • http://localhost:8001/product/ambre-precieux (Édition Limitée)"
echo ""
echo "📖 Guide complet : README_VENTE.md"
echo ""
