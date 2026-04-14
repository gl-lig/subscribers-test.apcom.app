# MISE EN SERVICE — bat-id Subscribers

## Ce que vous devez transmettre à Kreativ Medias

Envoyez ce message à votre hébergeur Kreativ Medias pour qu'ils mettent l'application en ligne :

---

**Objet : Mise en service subscribers-test.apcom.app**

Bonjour,

Merci de bien vouloir mettre en service l'application Laravel qui se trouve sur le dépôt Git suivant :

**Dépôt Git :** https://github.com/gl-lig/subscribers-test.apcom.app.git  
**Domaine :** subscribers-test.apcom.app  
**Utilisateur Plesk :** apcom_bat_i

**Base de données (déjà créée) :**
- Nom : `apcom_subscribers_test`
- Utilisateur : `apcom_sub_test`
- Mot de passe : `WoJHbziio2jn4_*1`

**Prérequis serveur :**
- PHP 8.2+
- MySQL/MariaDB
- Node.js (pour le build frontend)
- Pas besoin de Redis — tout fonctionne avec le filesystem et la base de données

**Étapes à effectuer :**

1. Cloner le dépôt dans le répertoire du domaine
2. Configurer le **Document Root** vers le sous-dossier `/public`
3. Exécuter les commandes d'installation :
   ```
   composer install --no-dev --optimize-autoloader
   npm ci && npm run build
   php artisan key:generate
   php artisan migrate --force
   php artisan db:seed --force
   php artisan storage:link
   php artisan config:cache && php artisan route:cache && php artisan view:cache
   ```
4. Configurer la **tâche planifiée** (cron) :
   ```
   * * * * * cd /chemin/vers/httpdocs && php artisan schedule:run >> /dev/null 2>&1
   ```
5. Configurer un **worker de queue** (Supervisor recommandé) :
   ```
   php artisan queue:work database --sleep=3 --tries=3 --max-time=3600
   ```

Merci beaucoup !

---

## Une fois le site en ligne

### Accéder au site public
Ouvrez dans votre navigateur : **https://subscribers-test.apcom.app**  
Vous verrez la page d'abonnements avec 3 offres (Starter, Premium, Enterprise).

### Accéder à l'administration
1. Ouvrez : **https://subscribers-test.apcom.app/admin/login**
2. Connectez-vous :
   - Email : **gregory.liand@apcom.ch**
   - Mot de passe : **1996**

### Tester un achat (mode test)
1. Cliquez sur **Commander** sur un abonnement
2. Entrez le numéro de test : **+41 79 209 44 78**
3. Vous arrivez dans le panier
4. Testez le code promo **TEST20** (donne 20% de réduction)
5. Le paiement Datatrans est configuré en mode sandbox — utilisez les cartes de test Datatrans

### Données de test créées automatiquement
- 3 abonnements : Starter (CHF 49/an), Premium (CHF 149/an), Enterprise (CHF 349/an)
- 1 abonné test : BAT-TEST-0001
- 1 commande test : CMD-000001
- 1 code promo : TEST20 (20% de réduction)

---

## Ce qui reste à faire (pas urgent)

- Configurer l'email SMTP dans Plesk — pour que les notifications admin fonctionnent
- Recevoir l'API bat-id.ch (URL + clé) — avant la mise en production
