#!/bin/bash
# =============================================================
# SCRIPT DE DÉPLOIEMENT — bat-id Subscribers
# Ce script est exécuté automatiquement après chaque git pull
# sur le serveur Plesk (Kreativ Medias)
# =============================================================

set -e

echo "🚀 Déploiement bat-id Subscribers..."

# Installer les dépendances PHP
composer install --no-dev --optimize-autoloader --no-interaction

# Installer les dépendances Node.js et compiler les assets
npm ci
npm run build

# Appliquer les migrations
php artisan migrate --force

# Vider les caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Créer le lien symbolique storage
php artisan storage:link 2>/dev/null || true

# Redémarrer les queues
php artisan queue:restart

echo "✅ Déploiement terminé !"
