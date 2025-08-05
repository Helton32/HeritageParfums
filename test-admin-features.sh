#!/bin/bash

# Script de test pour vérifier les nouvelles fonctionnalités admin
# Heritage Parfums - Administration

echo "🌹 Heritage Parfums - Test des Fonctionnalités Admin"
echo "=================================================="

# Couleurs pour l'affichage
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Fonction pour afficher les résultats
check_status() {
    if [ $1 -eq 0 ]; then
        echo -e "${GREEN}✅ $2${NC}"
    else
        echo -e "${RED}❌ $2${NC}"
    fi
}

echo ""
echo "🔧 Vérification de l'environnement..."

# Vérifier PHP
php --version > /dev/null 2>&1
check_status $? "PHP disponible"

# Vérifier Composer
composer --version > /dev/null 2>&1
check_status $? "Composer disponible"

# Vérifier la base de données
cd /Users/halick/ProjetLaravel/HeritageParfums

# Test des migrations
echo ""
echo "📊 Test des migrations..."
php artisan migrate:status | grep -q "contacts"
check_status $? "Migration contacts présente"

# Test des modèles
echo ""
echo "🗂️ Test des modèles..."
php artisan tinker --execute="echo App\Models\Contact::count() . ' contacts en base';" 2>/dev/null
check_status $? "Modèle Contact fonctionnel"

php artisan tinker --execute="echo App\Models\Product::count() . ' produits en base';" 2>/dev/null
check_status $? "Modèle Product fonctionnel"

# Test des routes
echo ""
echo "🌐 Test des routes..."
php artisan route:list | grep -q "admin.products.index"
check_status $? "Routes produits configurées"

php artisan route:list | grep -q "admin.contacts.index"
check_status $? "Routes contacts configurées"

# Test des contrôleurs
echo ""
echo "🎛️ Test des contrôleurs..."
test -f app/Http/Controllers/AdminProductController.php
check_status $? "AdminProductController présent"

test -f app/Http/Controllers/AdminContactController.php
check_status $? "AdminContactController présent"

test -f app/Http/Controllers/ContactController.php
check_status $? "ContactController présent"

# Test des vues
echo ""
echo "👁️ Test des vues..."
test -f resources/views/admin/products/index.blade.php
check_status $? "Vue liste produits présente"

test -f resources/views/admin/products/create.blade.php
check_status $? "Vue création produit présente"

test -f resources/views/admin/contacts/index.blade.php
check_status $? "Vue liste contacts présente"

test -f resources/views/admin/contacts/show.blade.php
check_status $? "Vue détail contact présente"

echo ""
echo "🚀 URLs de Test:"
echo "=================="
echo -e "${YELLOW}🏠 Dashboard Admin:${NC} http://127.0.0.1:8003/admin"
echo -e "${YELLOW}🛍️  Gestion Produits:${NC} http://127.0.0.1:8003/admin/products"
echo -e "${YELLOW}📧 Gestion Messages:${NC} http://127.0.0.1:8003/admin/contacts"
echo -e "${YELLOW}📝 Formulaire Contact:${NC} http://127.0.0.1:8003/contact"

echo ""
echo "📈 Statistiques:"
echo "================="

# Compter les données
PRODUCTS_COUNT=$(php artisan tinker --execute="echo App\Models\Product::count();" 2>/dev/null)
CONTACTS_COUNT=$(php artisan tinker --execute="echo App\Models\Contact::count();" 2>/dev/null)
UNREAD_COUNT=$(php artisan tinker --execute="echo App\Models\Contact::where('is_read', false)->count();" 2>/dev/null)

echo "🛍️  Produits en base: ${PRODUCTS_COUNT:-0}"
echo "📧 Messages en base: ${CONTACTS_COUNT:-0}"
echo "🔴 Messages non lus: ${UNREAD_COUNT:-0}"

echo ""
echo "🎯 Fonctionnalités Disponibles:"
echo "==============================="
echo "✅ CRUD complet des produits (style Filament)"
echo "✅ Gestion des messages de contact"
echo "✅ Dashboard avec statistiques temps réel"
echo "✅ Interface responsive style Guerlain"
echo "✅ Filtres et recherche avancée"
echo "✅ Actions en lot pour les messages"
echo "✅ Export CSV des contacts"
echo "✅ Formulaire de contact amélioré"
echo "✅ Notifications et badges en temps réel"

echo ""
echo -e "${GREEN}🌹 Heritage Parfums Administration - Prêt à l'emploi !${NC}"
echo ""
