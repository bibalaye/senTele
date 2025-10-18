#!/bin/bash

# Attendre que la base de données soit prête
echo "Waiting for database..."
sleep 5

# Vérifier si des migrations sont en attente
echo "Checking for pending migrations..."
PENDING_MIGRATIONS=$(php artisan migrate:status --pending 2>&1 | grep -c "Pending" || echo "0")

if [ "$PENDING_MIGRATIONS" -gt "0" ] || [ ! -f "/app/storage/.db_initialized" ]; then
    echo "⚠️  Pending migrations detected or first deployment"
    
    # Si c'est le premier déploiement, faire un fresh
    if [ ! -f "/app/storage/.db_initialized" ]; then
        echo "🔄 First deployment - Running fresh migrations..."
        php artisan migrate:fresh --force
        
        # Exécuter les seeders
        echo "Running database seeders..."
        php artisan db:seed --force
        php artisan db:seed --class=SubscriptionPlanSeeder --force
        php artisan db:seed --class=PromoCodeSeeder --force
        
        # Configurer l'utilisateur admin
        echo "Setting up admin user..."
        php artisan tinker --execute="
        \$user = App\Models\User::where('email', 'admin@sentele.com')->first();
        if (\$user) {
            \$user->is_admin = true;
            \$user->save();
            echo '✅ Admin user configured';
        }
        "
        
        # Importer des chaînes de test
        echo "Importing test channels..."
        php artisan channels:import https://iptv-org.github.io/iptv/categories/sports.m3u --category=Sports --plan=basic || true
        php artisan channels:import https://iptv-org.github.io/iptv/categories/news.m3u --category=Actualités --plan=basic || true
        php artisan channels:import https://iptv-org.github.io/iptv/categories/entertainment.m3u --category=Divertissement --plan=basic || true
        
        # Marquer comme initialisé
        touch /app/storage/.db_initialized
        echo "✅ Database initialized successfully"
    else
        # Sinon, juste exécuter les nouvelles migrations
        echo "🔄 Running pending migrations..."
        php artisan migrate --force
        echo "✅ Migrations completed"
    fi
else
    echo "✅ No pending migrations - database is up to date"
fi

# Toujours s'assurer que l'admin existe et est configuré
echo "Verifying admin user..."
php artisan tinker --execute="
\$user = App\Models\User::where('email', 'admin@sentele.com')->first();
if (\$user && !\$user->is_admin) {
    \$user->is_admin = true;
    \$user->save();
    echo '✅ Admin status updated';
}
" || true

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
