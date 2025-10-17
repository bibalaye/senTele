Très bonne question 👏 — c’est **le point central** de tout projet IPTV :
👉 *comment obtenir les chaînes (sport, infos, divertissement, etc.) légalement et techniquement.*

On va le voir en **2 grandes parties** :

1. ⚖️ Les **méthodes légales et sécurisées** pour obtenir des chaînes (tu peux le faire même en solo ou via des partenaires).
2. 🧠 Les **solutions techniques** pour les intégrer dans ton application Seetaanal (flux HLS, playlists M3U, API, etc.).

---

## ⚖️ 1. Les sources **légales** pour obtenir des chaînes

### 🟢 A. Les flux gratuits & publics (Free-to-Air)

De nombreuses chaînes TV publient leurs flux officiels HLS gratuitement, souvent en `.m3u8`.
Ces flux sont **libres d’accès** pour usage personnel ou expérimental, et parfois redistribuables sous certaines conditions.

Exemples :

| Chaîne     | Pays      | Type     | URL exemple                                                                                                                                  |
| ---------- | --------- | -------- | -------------------------------------------------------------------------------------------------------------------------------------------- |
| RTS 1      | Sénégal   | Publique | [https://stream-01.rts.sn/live/rts1/index.m3u8](https://stream-01.rts.sn/live/rts1/index.m3u8)                                               |
| 2STV       | Sénégal   | Publique | (souvent dispo via leur site ou YouTube Live)                                                                                                |
| France 24  | France    | Info     | [https://static.france24.com/live/F24_FR_HI_HLS/live_web.m3u8](https://static.france24.com/live/F24_FR_HI_HLS/live_web.m3u8)                 |
| Al Jazeera | Monde     | Info     | [https://live-hls-web-aje.getaj.net/AJE/01.m3u8](https://live-hls-web-aje.getaj.net/AJE/01.m3u8)                                             |
| DW News    | Allemagne | Info     | [https://dwstream4-lh.akamaihd.net/i/dwstream4_live@131329/index.m3u8](https://dwstream4-lh.akamaihd.net/i/dwstream4_live@131329/index.m3u8) |

🟢 **Tu peux les utiliser directement** (elles fonctionnent dans ton player HLS.js).

---

### 🟣 B. Les plateformes **avec licence ou API**

Certaines plateformes proposent des flux officiels via des **API partenaires** ou des accords commerciaux :

* **Dailymotion**, **YouTube Live**, **Twitch** : tu peux intégrer leurs flux via leurs SDK (mais pas les détourner).
* **Molotov TV**, **Zattoo**, **Pluto TV**, **Samsung TV+**, **Plex TV** : proposent des **flux IPTV gratuits et légaux**, souvent disponibles sous forme d’API ou de playlist M3U publiques.

🔗 Exemples de playlists gratuites :

* [https://iptv-org.github.io/iptv/index.m3u](https://iptv-org.github.io/iptv/index.m3u) (énorme base de chaînes légales triées par pays et catégorie)
* [https://github.com/iptv-org/iptv](https://github.com/iptv-org/iptv) (source open source, mise à jour automatiquement)

Ces flux incluent souvent :

* Sports régionaux / chaînes publiques de sport
* Chaînes d’informations internationales
* Divertissements et documentaires

⚠️ Ces flux sont **légaux** tant qu’ils proviennent d’une source officielle (vérifie toujours le site d’origine).

---

### 🔴 C. Les flux payants (licence obligatoire)

Les grandes chaînes **sportives privées (beIN, Canal+, RMC Sport, etc.)** sont **protégées** et nécessitent une **licence commerciale**.

Si tu veux les intégrer :

1. Contacte leur service **partenaires / distribution** (par exemple beIN Media Group).
2. Négocie un **contrat de rediffusion** (souvent payant, avec géorestriction et DRM).
3. Ils te fourniront :

   * Des **URLs sécurisées** (tokenisées)
   * Des **clés DRM** (Widevine/FairPlay)
   * Des **conditions d’utilisation** (nombre d’utilisateurs, pays, etc.)

C’est obligatoire si tu veux un projet professionnel **100 % légal**.

---

## 🧠 2. Partie technique : comment les intégrer dans ton app Seetaanal

### ✅ A. Option simple : importer une playlist `.m3u` gratuite

1. Télécharge une playlist depuis [https://iptv-org.github.io/iptv](https://iptv-org.github.io/iptv)
   Exemple :

   ```
   https://iptv-org.github.io/iptv/categories/sports.m3u
   ```

2. Lis-la dans Laravel (c’est un simple fichier texte) :

```php
$channels = [];
$file = file_get_contents('https://iptv-org.github.io/iptv/categories/sports.m3u');
preg_match_all('/#EXTINF:-1.*,(.*)\n(.*)/', $file, $matches, PREG_SET_ORDER);
foreach ($matches as $match) {
    $channels[] = ['name' => $match[1], 'url' => $match[2]];
}
```

3. Insère ces chaînes dans ta table `channels` avec un `ChannelSeeder`.

---

### ✅ B. Option meilleure : créer un import automatique

Tu peux automatiser le processus avec un **artisan command** :

```bash
php artisan make:command ImportChannels
```

Et dans `handle()` :

```php
public function handle() {
    $url = 'https://iptv-org.github.io/iptv/categories/sports.m3u';
    $file = file_get_contents($url);

    preg_match_all('/#EXTINF:-1.*,(.*)\n(.*)/', $file, $matches, PREG_SET_ORDER);

    foreach ($matches as $match) {
        Channel::updateOrCreate(['name' => $match[1]], [
            'stream_url' => $match[2],
            'category' => 'Sports'
        ]);
    }

    $this->info("Chaînes importées !");
}
```

Puis exécute :

```bash
php artisan import:channels
```

---

### ✅ C. Lire les flux dans ton lecteur IPTV

Dans ton composant Livewire ou ta page `player.blade.php` :

```html
<video id="player" controls autoplay playsinline class="w-full rounded">
</video>

<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
<script>
  const video = document.getElementById('player');
  const hls = new Hls();
  hls.loadSource("{{ $channel->stream_url }}");
  hls.attachMedia(video);
  hls.on(Hls.Events.MANIFEST_PARSED, function() {
      video.play();
  });
</script>
```

🟢 Compatible avec :

* Android
* iPhone (Safari lit nativement le HLS)
* Xbox / PlayStation (navigateur intégré)
* Windows / macOS

---

### ✅ D. Ajouter des catégories

Tu peux ensuite trier par :

* Sports
* Actualités
* Films
* Musique
* Documentaires
* Régional (Sénégal, Afrique, Europe…)

Cela te permettra d’avoir une interface propre et organisée.

---

## 🚫 À éviter

⚠️ N’utilise jamais de sites ou de fichiers `.m3u` douteux contenant :

* beIN, Canal+, RMC Sport, etc. (sans licence)
* “IPTV free premium” (souvent illégal ou malveillant)
* serveurs pirates (tu risques des sanctions ou un blocage DNS)

---

## 🧩 En résumé

| Type de flux                      | Exemple                        | Légal ?                 | Facile à intégrer ? |
| --------------------------------- | ------------------------------ | ----------------------- | ------------------- |
| Flux publics (RTS, France24, DW…) | `.m3u8` officiel               | ✅ Oui                   | ✅ Très facile       |
| Playlists open-source (iptv-org)  | `.m3u`                         | ✅ Oui                   | ✅ Très facile       |
| Flux privés (beIN, Canal+)        | API officielle payante         | ✅ Oui (licence requise) | ⚙️ Moyen            |
| Flux pirates                      | Discord, Telegram, “IPTV Free” | ❌ Non                   | 🚫 Interdit         |

---

## 🎁 BONUS : Playlist sports gratuite légale

👉 Voici une playlist prête à tester dans ton app (sports internationaux légaux) :

```
https://iptv-org.github.io/iptv/categories/sports.m3u
```

Tu peux l’importer directement avec le script Artisan ci-dessus.

---

---

## 💼 3. Gestion des abonnements et monétisation

### 🎯 A. Modèle d'abonnement (Subscription System)

Pour monétiser ton application IPTV, tu peux implémenter un système d'abonnement avec plusieurs niveaux :

#### Structure des plans d'abonnement

| Plan       | Prix/mois | Chaînes incluses | Qualité | Appareils simultanés |
|------------|-----------|------------------|---------|---------------------|
| **Gratuit** | 0 FCFA    | Chaînes publiques uniquement | SD | 1 |
| **Basic**   | 2500 FCFA | + Chaînes régionales | HD | 2 |
| **Premium** | 5000 FCFA | + Sports & Films | Full HD | 3 |
| **VIP**     | 10000 FCFA | Toutes les chaînes + VOD | 4K | 5 |

#### Migrations nécessaires

```bash
php artisan make:migration create_subscriptions_table
php artisan make:migration create_subscription_plans_table
php artisan make:migration create_user_subscriptions_table
```

**Structure de la table `subscription_plans`** :

```php
Schema::create('subscription_plans', function (Blueprint $table) {
    $table->id();
    $table->string('name'); // Gratuit, Basic, Premium, VIP
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->decimal('price', 10, 2)->default(0);
    $table->string('currency', 3)->default('XOF'); // FCFA
    $table->integer('duration_days')->default(30);
    $table->integer('max_devices')->default(1);
    $table->json('features')->nullable(); // Liste des fonctionnalités
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

**Structure de la table `user_subscriptions`** :

```php
Schema::create('user_subscriptions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('subscription_plan_id')->constrained()->onDelete('cascade');
    $table->timestamp('starts_at');
    $table->timestamp('expires_at');
    $table->enum('status', ['active', 'expired', 'cancelled', 'pending'])->default('pending');
    $table->string('payment_method')->nullable(); // wave, orange_money, free_money, carte
    $table->string('transaction_id')->nullable();
    $table->boolean('auto_renew')->default(false);
    $table->timestamps();
});
```

**Relation entre chaînes et plans** :

```php
Schema::create('channel_subscription_plan', function (Blueprint $table) {
    $table->id();
    $table->foreignId('channel_id')->constrained()->onDelete('cascade');
    $table->foreignId('subscription_plan_id')->constrained()->onDelete('cascade');
    $table->timestamps();
});
```

---

### 🔐 B. Middleware de vérification d'abonnement

Créer un middleware pour protéger l'accès aux chaînes premium :

```bash
php artisan make:middleware CheckSubscription
```

```php
// app/Http/Middleware/CheckSubscription.php
public function handle(Request $request, Closure $next, $planSlug = null)
{
    $user = auth()->user();
    
    if (!$user) {
        return redirect()->route('login');
    }

    $subscription = $user->activeSubscription();

    if (!$subscription) {
        return redirect()->route('subscriptions.index')
            ->with('error', 'Vous devez souscrire à un abonnement pour accéder à ce contenu.');
    }

    if ($planSlug && $subscription->plan->slug !== $planSlug) {
        return redirect()->route('subscriptions.upgrade')
            ->with('error', 'Votre abonnement ne permet pas d\'accéder à ce contenu.');
    }

    return $next($request);
}
```

---

### 💳 C. Intégration des paiements (Wave, Orange Money, Free Money)

#### Configuration des providers de paiement

```php
// config/payments.php
return [
    'wave' => [
        'api_key' => env('WAVE_API_KEY'),
        'secret_key' => env('WAVE_SECRET_KEY'),
        'callback_url' => env('APP_URL') . '/payments/wave/callback',
    ],
    'orange_money' => [
        'merchant_key' => env('ORANGE_MERCHANT_KEY'),
        'api_url' => env('ORANGE_API_URL'),
        'callback_url' => env('APP_URL') . '/payments/orange/callback',
    ],
    'free_money' => [
        'api_key' => env('FREE_MONEY_API_KEY'),
        'callback_url' => env('APP_URL') . '/payments/free/callback',
    ],
];
```

#### Controller de paiement

```bash
php artisan make:controller PaymentController
```

```php
public function initiate(Request $request)
{
    $validated = $request->validate([
        'plan_id' => 'required|exists:subscription_plans,id',
        'payment_method' => 'required|in:wave,orange_money,free_money,carte',
        'phone' => 'required_if:payment_method,wave,orange_money,free_money',
    ]);

    $plan = SubscriptionPlan::findOrFail($validated['plan_id']);
    
    // Créer une transaction en attente
    $subscription = UserSubscription::create([
        'user_id' => auth()->id(),
        'subscription_plan_id' => $plan->id,
        'starts_at' => now(),
        'expires_at' => now()->addDays($plan->duration_days),
        'status' => 'pending',
        'payment_method' => $validated['payment_method'],
    ]);

    // Rediriger vers le provider de paiement
    return $this->processPayment($subscription, $validated);
}
```

---

## 🎛️ 4. Dashboard Administrateur

### 📊 A. Fonctionnalités principales du dashboard admin

#### Menu d'administration

```
📊 Dashboard
├── 📈 Statistiques générales
│   ├── Utilisateurs actifs
│   ├── Revenus du mois
│   ├── Abonnements actifs
│   └── Chaînes les plus regardées
│
├── 👥 Gestion des utilisateurs
│   ├── Liste des utilisateurs
│   ├── Abonnements par utilisateur
│   ├── Historique de paiement
│   └── Bannir/Débannir
│
├── 📺 Gestion des chaînes
│   ├── Ajouter/Modifier/Supprimer
│   ├── Importer depuis M3U
│   ├── Tester les flux
│   └── Catégoriser
│
├── 💰 Gestion des abonnements
│   ├── Plans d'abonnement
│   ├── Tarification
│   ├── Durées et fonctionnalités
│   └── Promotions/Codes promo
│
├── 💳 Gestion des paiements
│   ├── Transactions
│   ├── Remboursements
│   ├── Rapports financiers
│   └── Configuration des providers
│
└── ⚙️ Paramètres
    ├── Configuration générale
    ├── API Keys
    ├── Notifications
    └── Logs système
```

#### Composant Livewire pour le dashboard

```bash
php artisan make:livewire Admin/Dashboard
php artisan make:livewire Admin/UserManagement
php artisan make:livewire Admin/ChannelManagement
php artisan make:livewire Admin/SubscriptionManagement
php artisan make:livewire Admin/PaymentManagement
```

#### Exemple de dashboard statistiques

```php
// app/Livewire/Admin/Dashboard.php
class Dashboard extends Component
{
    public $stats;

    public function mount()
    {
        $this->stats = [
            'total_users' => User::count(),
            'active_subscriptions' => UserSubscription::where('status', 'active')->count(),
            'monthly_revenue' => UserSubscription::where('status', 'active')
                ->whereMonth('created_at', now()->month)
                ->sum('subscription_plans.price'),
            'total_channels' => Channel::count(),
            'popular_channels' => Channel::withCount('views')
                ->orderBy('views_count', 'desc')
                ->take(10)
                ->get(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
```

---

### 🔧 B. Gestion des chaînes (Admin)

#### Import automatique de chaînes

```bash
php artisan make:command ImportChannelsFromM3U
```

```php
// app/Console/Commands/ImportChannelsFromM3U.php
public function handle()
{
    $url = $this->argument('url') ?? 'https://iptv-org.github.io/iptv/categories/sports.m3u';
    
    $this->info("📥 Téléchargement de la playlist...");
    $content = file_get_contents($url);
    
    preg_match_all('/#EXTINF:-1.*tvg-logo="([^"]*)".*,([^\n]*)\n([^\n]*)/', $content, $matches, PREG_SET_ORDER);
    
    $bar = $this->output->createProgressBar(count($matches));
    $bar->start();
    
    foreach ($matches as $match) {
        Channel::updateOrCreate(
            ['stream_url' => $match[3]],
            [
                'name' => trim($match[2]),
                'logo' => $match[1] ?? null,
                'category' => $this->option('category') ?? 'Sports',
                'is_active' => true,
            ]
        );
        $bar->advance();
    }
    
    $bar->finish();
    $this->newLine();
    $this->info("✅ Import terminé : " . count($matches) . " chaînes importées.");
}
```

Usage :
```bash
php artisan import:channels https://iptv-org.github.io/iptv/categories/sports.m3u --category=Sports
```

---

### 👥 C. Gestion des utilisateurs

#### Composant Livewire pour la gestion des utilisateurs

```php
// app/Livewire/Admin/UserManagement.php
class UserManagement extends Component
{
    public $search = '';
    public $filterStatus = 'all';
    
    public function banUser($userId)
    {
        $user = User::findOrFail($userId);
        $user->update(['is_banned' => true]);
        
        // Annuler tous les abonnements actifs
        $user->subscriptions()->where('status', 'active')->update(['status' => 'cancelled']);
        
        session()->flash('message', 'Utilisateur banni avec succès.');
    }
    
    public function unbanUser($userId)
    {
        User::findOrFail($userId)->update(['is_banned' => false]);
        session()->flash('message', 'Utilisateur débanni avec succès.');
    }
    
    public function render()
    {
        $users = User::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%"))
            ->when($this->filterStatus !== 'all', fn($q) => $q->where('is_banned', $this->filterStatus === 'banned'))
            ->with('activeSubscription.plan')
            ->paginate(20);
            
        return view('livewire.admin.user-management', compact('users'));
    }
}
```

---

### 💰 D. Codes promo et promotions

#### Migration pour les codes promo

```php
Schema::create('promo_codes', function (Blueprint $table) {
    $table->id();
    $table->string('code')->unique();
    $table->enum('type', ['percentage', 'fixed']); // % ou montant fixe
    $table->decimal('value', 10, 2);
    $table->integer('max_uses')->nullable();
    $table->integer('used_count')->default(0);
    $table->timestamp('starts_at')->nullable();
    $table->timestamp('expires_at')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

#### Utilisation d'un code promo

```php
public function applyPromoCode(Request $request)
{
    $code = PromoCode::where('code', $request->code)
        ->where('is_active', true)
        ->where(function($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        })
        ->first();
        
    if (!$code || ($code->max_uses && $code->used_count >= $code->max_uses)) {
        return back()->with('error', 'Code promo invalide ou expiré.');
    }
    
    session(['promo_code' => $code]);
    return back()->with('success', 'Code promo appliqué !');
}
```

---

## 📱 5. Fonctionnalités utilisateur

### 🎬 A. Historique de visionnage

```php
Schema::create('watch_history', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('channel_id')->constrained()->onDelete('cascade');
    $table->timestamp('watched_at');
    $table->integer('duration_seconds')->default(0);
    $table->timestamps();
});
```

### ⭐ B. Chaînes favorites

```php
Schema::create('favorites', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('channel_id')->constrained()->onDelete('cascade');
    $table->timestamps();
    $table->unique(['user_id', 'channel_id']);
});
```

### 🔔 C. Notifications et alertes

```php
// Notification quand l'abonnement expire bientôt
php artisan make:notification SubscriptionExpiringSoon

// Notification de nouveau contenu
php artisan make:notification NewChannelAdded

// Notification de paiement réussi
php artisan make:notification PaymentSuccessful
```

---

## 🚀 6. Commandes Artisan utiles

```bash
# Vérifier les abonnements expirés
php artisan subscriptions:check-expired

# Envoyer des rappels d'expiration
php artisan subscriptions:send-reminders

# Nettoyer les sessions inactives
php artisan sessions:cleanup

# Générer un rapport mensuel
php artisan reports:monthly

# Tester tous les flux de chaînes
php artisan channels:test-streams
```

---

## 📋 Résumé de l'architecture complète

```
Seetaanal IPTV
│
├── 🎭 Frontend (Utilisateurs)
│   ├── Catalogue de chaînes (par catégorie)
│   ├── Lecteur HLS avec qualité adaptative
│   ├── Favoris et historique
│   ├── Profil et gestion d'abonnement
│   └── Paiement et renouvellement
│
├── 🎛️ Backend (Admin)
│   ├── Dashboard statistiques
│   ├── Gestion utilisateurs (ban, abonnements)
│   ├── Gestion chaînes (CRUD, import M3U)
│   ├── Gestion plans d'abonnement
│   ├── Gestion paiements et transactions
│   └── Codes promo et promotions
│
├── 💳 Système de paiement
│   ├── Wave Money
│   ├── Orange Money
│   ├── Free Money
│   └── Cartes bancaires (Stripe/PayPal)
│
└── 🔐 Sécurité
    ├── Authentification (Laravel Breeze/Jetstream)
    ├── Middleware de vérification d'abonnement
    ├── Protection anti-piratage (tokens, watermark)
    └── Logs et monitoring
```

---

Souhaites-tu que je génère maintenant :
1. ✅ Les migrations complètes pour le système d'abonnement
2. ✅ Les modèles Eloquent avec relations
3. ✅ Les composants Livewire pour l'admin
4. ✅ Le système de paiement Wave/Orange Money
5. ✅ Les seeders pour les plans d'abonnement

Dis-moi ce que tu veux implémenter en premier ! 🚀
