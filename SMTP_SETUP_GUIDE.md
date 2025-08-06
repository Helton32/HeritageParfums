# Guide de Configuration SMTP - Heritage Parfums

## 🎯 Recommandations par cas d'usage

### 💰 **Budget limité (Gratuit)**
- **Brevo (Sendinblue)** : 300 emails/jour gratuits ✅ RECOMMANDÉ
- **Gmail** : 500 emails/jour mais peut être bloqué
- **Mailtrap** : Parfait pour les tests

### 🏢 **Usage professionnel**
- **Brevo Pro** : €25/mois pour 20,000 emails
- **SendGrid** : $19.95/mois pour 100,000 emails
- **Mailgun** : $35/mois pour 50,000 emails

---

## 1️⃣ Configuration Brevo (Recommandé)

### Étape 1: Créer un compte Brevo
1. Allez sur [brevo.com](https://brevo.com)
2. Créez un compte gratuit
3. Confirmez votre email

### Étape 2: Obtenir les identifiants SMTP
1. Connectez-vous à Brevo
2. Allez dans **SMTP & API** → **SMTP**
3. Notez vos identifiants :
   - **Login** : votre email Brevo
   - **Clé SMTP** : générée automatiquement

### Étape 3: Configuration Laravel
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@example.com
MAIL_PASSWORD=votre-cle-smtp-brevo
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="contact@heritage-parfums.fr"
MAIL_FROM_NAME="Heritage Parfums"
```

---

## 2️⃣ Configuration Gmail

### Étape 1: Activer l'authentification à 2 facteurs
1. Allez dans votre compte Google
2. Sécurité → Authentification à 2 facteurs
3. Activez-la

### Étape 2: Générer un mot de passe d'application
1. Google Account → Sécurité
2. Mots de passe d'application
3. Sélectionnez "Autre" → tapez "Heritage Parfums"
4. Copiez le mot de passe généré (16 caractères)

### Étape 3: Configuration Laravel
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=mot-de-passe-application-16-caracteres
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="votre-email@gmail.com"
MAIL_FROM_NAME="Heritage Parfums"
```

---

## 3️⃣ Configuration Mailtrap (Tests uniquement)

### Étape 1: Créer un compte
1. Allez sur [mailtrap.io](https://mailtrap.io)
2. Créez un compte gratuit
3. Créez une "Inbox"

### Étape 2: Obtenir les identifiants
1. Dans votre inbox Mailtrap
2. Cliquez sur "Show Credentials"
3. Sélectionnez "Laravel 9+"

### Étape 3: Configuration Laravel
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=username-from-mailtrap
MAIL_PASSWORD=password-from-mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="contact@heritage-parfums.fr"
MAIL_FROM_NAME="Heritage Parfums"
```

---

## 🔧 Configuration dans votre projet

1. **Sauvegardez votre .env actuel** :
   ```bash
   cp .env .env.backup
   ```

2. **Modifiez votre .env** avec la configuration choisie

3. **Videz le cache de configuration** :
   ```bash
   php artisan config:clear
   php artisan config:cache
   ```

4. **Testez la configuration** :
   ```bash
   php test_smtp_config.php
   ```

---

## 📋 Checklist de vérification

- [ ] Compte créé chez le fournisseur SMTP
- [ ] Identifiants SMTP obtenus
- [ ] Configuration ajoutée dans .env
- [ ] Cache Laravel vidé
- [ ] Test d'envoi réussi
- [ ] SPF/DKIM configurés (pour éviter les spams)

---

## 🚨 Erreurs courantes

### "Authentication failed"
- Vérifiez username/password
- Pour Gmail : utilisez un mot de passe d'application
- Pour Brevo : utilisez la clé SMTP, pas votre mot de passe compte

### "Connection refused"
- Vérifiez MAIL_HOST et MAIL_PORT
- Vérifiez que votre hébergeur n'bloque pas le port 587

### "SSL certificate problem"
- Utilisez TLS au lieu de SSL
- Mettez MAIL_ENCRYPTION=tls

### Emails dans les spams
- Configurez SPF : `v=spf1 include:spf.brevo.com ~all`
- Activez DKIM dans les paramètres de votre fournisseur
- Utilisez un domaine cohérent dans FROM_ADDRESS
