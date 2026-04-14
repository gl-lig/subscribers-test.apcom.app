# GUIDE D'INSTALLATION — bat-id Subscribers

## Prérequis serveur (déjà en place sur Kreativ Medias)

- PHP 8.2+ avec extensions : BCMath, Ctype, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, Redis
- MySQL / MariaDB
- Redis
- Composer
- Node.js 18+ et npm
- Git

---

## ÉTAPE 1 — Première installation

### 1.1 Connexion SSH au serveur

Connectez-vous en SSH au serveur Kreativ Medias (IP: 94.126.17.132) avec l'utilisateur `apcom_bat_i`.

### 1.2 Cloner le dépôt

```bash
cd /var/www/vhosts/bat-i.ch/subscribers-test.bat-i.ch/httpdocs
git clone https://github.com/gl-lig/subscribers-test.bat-i.ch.git .
```

> Si le dossier n'est pas vide, configurez le déploiement Git dans Plesk directement.

### 1.3 Installer les dépendances

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
```

### 1.4 Générer la clé d'application

```bash
php artisan key:generate
```

### 1.5 Créer les tables et insérer les données

```bash
php artisan migrate --force
php artisan db:seed --force
```

Cela va créer :
- ✅ Toutes les tables de la base de données
- ✅ Votre compte admin : `gregory.liand@apcom.ch` / mot de passe : `1996`
- ✅ 3 types d'abonnement (Starter, Premium, Enterprise)
- ✅ Un abonné de test (BAT-TEST-0001 / +41792094478)
- ✅ Une commande de test (CMD-000001)
- ✅ Un code promo de test : `TEST20` (20% de réduction)
- ✅ Les paramètres par défaut

### 1.6 Créer le lien storage

```bash
php artisan storage:link
```

### 1.7 Mettre en cache

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 1.8 Configurer le document root dans Plesk

Dans Plesk, configurez le **Document Root** du domaine `subscribers-test.bat-i.ch` vers :

```
/subscribers-test.bat-i.ch/httpdocs/public
```

> C'est le dossier `public/` de Laravel qui doit être le document root.

### 1.9 Configurer le cron Laravel

Dans Plesk → Tâches planifiées, ajoutez :

```
* * * * * cd /path/to/httpdocs && php artisan schedule:run >> /dev/null 2>&1
```

### 1.10 Lancer le worker de queue

```bash
php artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
```

> Idéalement, configurez un superviseur (Supervisor) pour que le worker redémarre automatiquement.

---

## ÉTAPE 2 — Tester l'application

### Page publique
1. Ouvrez `https://subscribers-test.bat-i.ch`
2. Vous devriez voir la page de tarification avec 3 abonnements

### Test d'achat (mode mock)
1. Cliquez sur **Commander** sur un abonnement
2. Entrez le numéro : `+41792094478` (numéro de test configuré)
3. Le système reconnaîtra l'utilisateur mock et vous amènera au panier
4. Testez le code promo `TEST20`

> ⚠️ Le paiement Datatrans nécessite un Merchant ID. Sans celui-ci, vous verrez une erreur au moment du paiement. C'est normal — l'intégration sera complète une fois les credentials reçus.

### Dashboard admin
1. Ouvrez `https://subscribers-test.bat-i.ch/admin/login`
2. Connectez-vous avec :
   - Email : `gregory.liand@apcom.ch`
   - Mot de passe : `1996`
3. La 2FA n'est pas encore configurée, vous accéderez directement au tableau de bord

---

## ÉTAPE 3 — Mises à jour futures

Après chaque push sur le dépôt Git, exécutez sur le serveur :

```bash
cd /path/to/httpdocs
git pull
bash deploy.sh
```

Ou configurez le déploiement automatique dans Plesk Git.

---

## CONFIGURATION À COMPLÉTER

| Élément | Où configurer | Statut |
|---------|--------------|--------|
| Credentials Datatrans sandbox | Fichier `.env` : `DATATRANS_MERCHANT_ID` et `DATATRANS_SIGN_KEY` | En attente |
| Credentials SMTP | Fichier `.env` : `MAIL_USERNAME` et `MAIL_PASSWORD` | À configurer dans Plesk |
| API bat-id.ch (production) | Fichier `.env` : `BAT_ID_OUTGOING_API_URL` et `BAT_ID_OUTGOING_API_KEY` | En attente |
| Passer en mode live | Fichier `.env` : `BAT_ID_API_MODE=live` | Après tests |

---

## STRUCTURE DES FICHIERS

```
├── app/                    # Code source PHP
│   ├── Contracts/          # Interfaces
│   ├── Events/             # Événements
│   ├── Http/Controllers/   # Contrôleurs
│   ├── Jobs/               # Jobs asynchrones
│   ├── Livewire/           # Composants Livewire
│   ├── Mail/               # Emails
│   ├── Models/             # Modèles Eloquent
│   └── Services/           # Services métier
├── config/                 # Configuration Laravel
├── database/
│   ├── migrations/         # Migrations DB
│   └── seeders/            # Données initiales
├── lang/                   # Traductions (fr/de/it/en)
├── public/                 # Point d'entrée web
├── resources/views/        # Templates Blade
├── routes/                 # Routes web et admin
├── storage/app/invoices/   # Factures PDF générées
└── .env                    # Configuration environnement
```

---

## AIDE

En cas de problème :
- Vérifiez les logs : `storage/logs/laravel.log`
- Vérifiez que Redis fonctionne : `redis-cli ping`
- Vérifiez les permissions : `chmod -R 775 storage bootstrap/cache`
