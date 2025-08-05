-- Script de vérification de la base de données Heritage Parfums
-- À exécuter dans phpMyAdmin pour vérifier la structure

USE heritage_parfums;

-- Afficher toutes les tables
SHOW TABLES;

-- Vérifier la structure des tables principales
DESCRIBE products;
DESCRIBE orders;
DESCRIBE order_items;

-- Compter les enregistrements
SELECT 
    'products' as table_name, 
    COUNT(*) as record_count 
FROM products
UNION ALL
SELECT 
    'users' as table_name, 
    COUNT(*) as record_count 
FROM users
UNION ALL
SELECT 
    'orders' as table_name, 
    COUNT(*) as record_count 
FROM orders;

-- Afficher quelques produits
SELECT 
    name as 'Nom du Parfum',
    price as 'Prix (€)',
    category as 'Catégorie',
    type as 'Type',
    stock as 'Stock'
FROM products 
ORDER BY category, name
LIMIT 10;

-- Afficher les parfums par catégorie
SELECT 
    category as 'Catégorie',
    COUNT(*) as 'Nombre de Parfums',
    AVG(price) as 'Prix Moyen (€)',
    MIN(price) as 'Prix Min (€)',
    MAX(price) as 'Prix Max (€)'
FROM products 
GROUP BY category
ORDER BY category;