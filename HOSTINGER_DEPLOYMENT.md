# 🚀 Guide de Déploiement Hostinger - Heritage Parfums

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

Si problème d'emails :
1. Vérifiez storage/logs/laravel.log
2. Testez SMTP avec un script simple  
3. Contactez support Hostinger

Votre système Heritage Parfums est prêt pour Hostinger ! 🎉
