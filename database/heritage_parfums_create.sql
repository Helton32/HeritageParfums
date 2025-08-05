-- Script de création de base de données Heritage Parfums pour MAMP
-- À exécuter dans phpMyAdmin

-- Créer la base de données
CREATE DATABASE IF NOT EXISTS heritage_parfums 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Utiliser la base de données
USE heritage_parfums;

-- Créer un utilisateur spécifique (optionnel, vous pouvez utiliser root)
-- CREATE USER 'heritage_user'@'localhost' IDENTIFIED BY 'heritage_password';
-- GRANT ALL PRIVILEGES ON heritage_parfums.* TO 'heritage_user'@'localhost';
-- FLUSH PRIVILEGES;

-- Afficher les informations de la base créée
SELECT 
    SCHEMA_NAME as 'Base de données',
    DEFAULT_CHARACTER_SET_NAME as 'Charset',
    DEFAULT_COLLATION_NAME as 'Collation'
FROM information_schema.SCHEMATA 
WHERE SCHEMA_NAME = 'heritage_parfums';