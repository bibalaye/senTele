#!/bin/bash

# Attendre que la base de données soit prête
echo "Waiting for database..."
sleep 5

# Exécuter les migrations
echo "Running migrations..."
php artisan migrate --force

# Exécuter les seeders (UserSeeder et ChannelSeeder via DatabaseSeeder)
echo "Running database seeders..."
php artisan db:seed --force

# Exécuter les seeders additionnels pour les plans et codes promo
echo "Running subscription plans seeder..."
php artisan db:seed --class=SubscriptionPlanSeeder --force

echo "Running promo codes seeder..."
php artisan db:seed --class=PromoCodeSeeder --force

# Optimiser l'application
echo "Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Démarrer l'application
echo "Starting application..."
php artisan serve --host=0.0.0.0 --port=8000
