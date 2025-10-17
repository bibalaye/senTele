#!/usr/bin/env bash
# exit on error
set -o errexit

composer install --no-dev --optimize-autoloader --no-interaction
npm ci
npm run build

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
