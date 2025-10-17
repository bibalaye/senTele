# 🚀 Guide de démarrage rapide - Seetaanal IPTV

## ⚡ Installation en 5 minutes

### 1️⃣ Exécuter les migrations

```bash
php artisan migrate
```

### 2️⃣ Créer les plans d'abonnement

```bash
php artisan db:seed --class=SubscriptionPlanSeeder
php artisan db:seed --class=PromoCodeSeeder
```

### 3️⃣ Créer un compte administrateur

Connectez-vous avec votre compte, puis dans Tinker :

```bash
php artisan tinker
```

```php
$user = User::where('email', 'votre@email.com')->first();
$user->is_admin = true;
$user->save();
exit
```

### 4️⃣ Importer des chaînes gratuites

```bash
# Chaînes sportives
php artisan channels:import https://iptv-org.github.io/iptv/categories/sports.m3u --category=Sports --plan=free

# Chaînes d'actualités
php artisan channels:import https://iptv-org.github.io/iptv/categories/news.m3u --category=Actualités --plan=free

# Chaînes de divertissement
php artisan channels:import https://iptv-org.github.io/iptv/categories/entertainment.m3u --category=Divertissement --plan=basic
```

### 5️⃣ Lancer l'application

```bash
php artisan serve
```

Accédez à : `http://localhost:8000`

---

## 🎯 Accès aux interfaces

### Interface utilisateur
- **Accueil** : `http://localhost:8000`
- **Chaînes** : `http://localhost:8000/channels`
- **Abonnements** : `http://localhost:8000/subscriptions`
- **Dashboard** : `http://localhost:8000/dashboard`

### Interface administrateur
- **Dashboard Admin** : `http://localhost:8000/admin/dashboard`
- **Gestion utilisateurs** : `http://localhost:8000/admin/users`
- **Gestion chaînes** : `http://localhost:8000/admin/channels`
- **Gestion plans** : `http://localhost:8000/admin/subscriptions`

---

## 📋 Fonctionnalités disponibles

### ✅ Pour les utilisateurs
- [x] Inscription / Connexion
- [x] Voir les chaînes disponibles
- [x] Ajouter des chaînes en favoris
- [x] Choisir un plan d'abonnement
- [x] Regarder les chaînes en direct (HLS)
- [x] Historique de visionnage
- [x] Gestion du profil

### ✅ Pour les administrateurs
- [x] Dashboard avec statistiques
- [x] Gestion des utilisateurs (ban/déban)
- [x] Gestion des chaînes (CRUD)
- [x] Import automatique de chaînes M3U
- [x] Gestion des plans d'abonnement
- [x] Codes promotionnels
- [x] Rapports et analytics

---

## 🎨 Plans d'abonnement créés

| Plan | Prix | Durée | Appareils |
|------|------|-------|-----------|
| **Gratuit** | 0 XOF | 365 jours | 1 |
| **Basic** | 2500 XOF | 30 jours | 2 |
| **Premium** | 5000 XOF | 30 jours | 3 |
| **VIP** | 10000 XOF | 30 jours | 5 |

---

## 🔧 Commandes utiles

### Gestion des abonnements
```bash
# Vérifier les abonnements expirés
php artisan subscriptions:check-expired
```

### Import de chaînes
```bash
# Import avec catégorie et plan personnalisés
php artisan channels:import [URL] --category=[Catégorie] --plan=[slug-du-plan]

# Exemples
php artisan channels:import https://example.com/playlist.m3u --category=Films --plan=premium
```

### Maintenance
```bash
# Nettoyer le cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Optimiser l'application
php artisan optimize
```

---

## 🧪 Tester l'application

### 1. Créer un utilisateur de test
```bash
php artisan tinker
```

```php
User::factory()->create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => bcrypt('password'),
]);
```

### 2. Attribuer un abonnement gratuit
```php
$user = User::where('email', 'test@example.com')->first();
$freePlan = SubscriptionPlan::where('slug', 'free')->first();

UserSubscription::create([
    'user_id' => $user->id,
    'subscription_plan_id' => $freePlan->id,
    'starts_at' => now(),
    'expires_at' => now()->addYear(),
    'status' => 'active',
]);
```

### 3. Tester le lecteur HLS
- Connectez-vous avec le compte test
- Allez sur `/channels`
- Cliquez sur une chaîne pour la regarder

---

## 🔐 Sécurité

### Protéger les routes
Les routes sont déjà protégées par les middlewares :
- `auth` : Utilisateur connecté
- `admin` : Utilisateur administrateur
- `subscription` : Vérification de l'abonnement

### Exemple d'utilisation
```php
Route::get('/premium-content', function() {
    // Contenu accessible uniquement avec un abonnement premium
})->middleware(['auth', 'subscription:premium']);
```

---

## 📱 PWA (Progressive Web App)

L'application est déjà configurée comme PWA :
- ✅ Manifest.json
- ✅ Service Worker
- ✅ Mode hors ligne
- ✅ Installation sur mobile

Pour tester :
1. Ouvrez l'app sur mobile
2. Cliquez sur "Ajouter à l'écran d'accueil"
3. L'app s'ouvre comme une application native

---

## 🎯 Prochaines étapes

### Intégration des paiements
1. Configurer Wave Money dans `.env`
2. Configurer Orange Money dans `.env`
3. Créer les contrôleurs de paiement
4. Tester les transactions

### Améliorer l'expérience
1. Ajouter des notifications par email
2. Créer une API REST pour mobile
3. Ajouter un système de VOD
4. Implémenter le contrôle parental

---

## 🆘 Dépannage

### Les chaînes ne se chargent pas
```bash
# Vérifier les URLs des chaînes
php artisan tinker
Channel::where('is_active', true)->get(['name', 'stream_url']);
```

### Erreur de migration
```bash
# Réinitialiser la base de données
php artisan migrate:fresh --seed
```

### Problème de permissions
```bash
# Windows (PowerShell en admin)
icacls storage /grant Users:F /T
icacls bootstrap/cache /grant Users:F /T
```

---

## 📚 Documentation

- [Laravel Documentation](https://laravel.com/docs)
- [Livewire Documentation](https://livewire.laravel.com/docs)
- [HLS.js Documentation](https://github.com/video-dev/hls.js/)
- [IPTV-Org Playlists](https://github.com/iptv-org/iptv)

---

## ✨ Félicitations !

Votre plateforme IPTV Seetaanal est maintenant opérationnelle ! 🎉

Pour toute question, consultez `IMPLEMENTATION_COMPLETE.md` ou `plane.md`.
