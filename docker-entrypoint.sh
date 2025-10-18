#!/bin/bash

# Attendre que la base de données soit prête
echo "Waiting for database..."
sleep 5

# Réinitialiser la base de données (drop toutes les tables et recréer)
echo "Resetting database..."
php artisan migrate:fresh --force

# Exécuter les seeders (UserSeeder et ChannelSeeder via DatabaseSeeder)
echo "Running database seeders..."
php artisan db:seed --force

# Exécuter les seeders additionnels pour les plans et codes promo
echo "Running subscription plans seeder..."
php artisan db:seed --class=SubscriptionPlanSeeder --force

echo "Running promo codes seeder..."
php artisan db:seed --class=PromoCodeSeeder --force

# Créer le lien symbolique pour le storage
echo "Creating storage link..."
php artisan storage:link || true

# Vérifier que les assets existent
echo "Checking assets..."
if [ ! -d "/app/public/build" ]; then
    echo "WARNING: /app/public/build directory not found! Running npm build..."
    npm run build
fi

# Optimiser l'application
echo "Optimizing application..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# S'assurer que les permissions sont correctes
echo "Setting permissions..."
chmod -R 755 /app/public
chmod -R 775 /app/storage /app/bootstrap/cache

# Démarrer l'application
echo "Starting application..."
php artisan serve --host=0.0.0.0 --port=8000
