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

# Configurer l'utilisateur admin
echo "Setting up admin user..."
php artisan tinker --execute="
\$user = App\Models\User::where('email', 'admin@sentele.com')->first();
if (\$user) {
    \$user->is_admin = true;
    \$user->save();
    echo '✅ Admin user configured successfully';
}
"

# Importer des chaînes de test
echo "Importing test channels..."
echo "Importing sports channels..."
php artisan channels:import https://iptv-org.github.io/iptv/categories/sports.m3u --category=Sports --plan=basic || echo "Sports import failed, continuing..."

echo "Importing news channels..."
php artisan channels:import https://iptv-org.github.io/iptv/categories/news.m3u --category=Actualités --plan=basic || echo "News import failed, continuing..."

echo "Importing entertainment channels..."
php artisan channels:import https://iptv-org.github.io/iptv/categories/entertainment.m3u --category=Divertissement --plan=basic || echo "Entertainment import failed, continuing..."

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
