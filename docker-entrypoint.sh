#!/bin/bash

# Attendre que la base de données soit prête
echo "Waiting for database..."
sleep 5

# Exécuter les migrations
echo "Running migrations..."
php artisan migrate --force

# Exécuter les seeders
echo "Running seeders..."
php artisan db:seed --force

# Optimiser l'application
echo "Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Démarrer l'application
echo "Starting application..."
php artisan serve --host=0.0.0.0 --port=8000
