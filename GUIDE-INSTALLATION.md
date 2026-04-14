# MISE EN SERVICE — bat-id Subscribers

## Ce que Claude Code gère (aucune intervention de Greg)

- Installation du projet sur le VPS Infomaniak via Coolify
- Configuration base de données, SSL, cron, queue worker
- Build frontend (npm run build)
- Seeders et migrations

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
