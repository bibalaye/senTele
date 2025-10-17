

## ⚙️ OBJECTIF : Version “accessible” de sentele V2

Une **plateforme IPTV full web**, sans serveur complexe ni microservices.
Tu utilises **Laravel + Livewire** pour le backend et **une PWA responsive** qui tourne dans le navigateur de **tous les appareils (même consoles)**.

---

## 🌐 Pourquoi cette approche fonctionne sur tout :

* **Xbox / PlayStation** : leurs navigateurs modernes supportent HTML5 + HLS.
* **PC / Mac** : navigateurs de bureau → parfait.
* **Android / iPhone** : PWA installable (donc apparait comme une app native).
  ✅ **Une seule app web**, donc zéro besoin d’app native.

---

## 🧱 Architecture simplifiée (idéale pour débuter)

Voici la version “accessible” du schéma global :

```
[Utilisateur] 
    ↓ (Navigateur / PWA)
[Frontend Laravel Livewire + Tailwind]
    ↓
[API Laravel]
    ↓
[Base de données MySQL]
    ↓
[Serveur streaming distant (flux HLS)]
```

---

## 🧩 STACK TECHNIQUE (simple mais pro)

| Partie                    | Outil                                 | Rôle                               |
| ------------------------- | ------------------------------------- | ---------------------------------- |
| **Backend**               | Laravel 11                            | API, gestion utilisateurs, chaînes |
| **Frontend**              | Livewire 3 + TailwindCSS              | Interface réactive, PWA            |
| **Streaming**             | HLS.js                                | Lecture vidéo fluide               |
| **Base de données**       | MySQL (ou SQLite pour début)          | Données chaînes/utilisateurs       |
| **Hébergement**           | Hostinger / Render / Laravel Forge    | Facile à déployer                  |
| **Stockage images/logos** | Laravel storage ou Cloudinary gratuit |                                    |
| **CDN (optionnel)**       | Cloudflare gratuit                    | Boost performance                  |

---

## 💡 Fonctionnalités de base à construire (MVP)

| Fonction            | Description                                   |
| ------------------- | --------------------------------------------- |
| 🔐 Authentification | Login, Register, Reset Password               |
| 📺 Liste de chaînes | Liste des flux IPTV disponibles               |
| 🎥 Lecteur intégré  | HLS.js + Player HTML5                         |
| 🔍 Recherche simple | Filtrer par nom ou catégorie                  |
| ❤️ Favoris          | Ajouter aux favoris (stockés côté DB)         |
| 🧠 Caching          | Cache Laravel + Browser Storage               |
| 📱 PWA              | Installable sur Android, iPhone, PC, Xbox, PS |
| ⚡ Design            | TailwindCSS, mode clair/sombre                |

---


### **Étape 2 : Crée la base de données**

Tables simples :

* `users` (déjà fournie par Laravel)
* `channels` (nom, logo, url, pays, catégorie)
* `favorites` (user_id, channel_id)

### Exemple de migration :

```php
Schema::create('channels', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('logo')->nullable();
    $table->string('country')->nullable();
    $table->string('category')->nullable();
    $table->string('stream_url');
    $table->timestamps();
});
```

---

### **Étape 3 : Crée un composant Livewire pour afficher les chaînes**

```bash
php artisan make:livewire ChannelList
```

Dans `ChannelList.php` :

```php
public $search = '';

public function render()
{
    $channels = Channel::where('name', 'like', "%{$this->search}%")->get();
    return view('livewire.channel-list', compact('channels'));
}
```

Dans `channel-list.blade.php` :

```html
<div>
  <input wire:model="search" placeholder="Rechercher une chaîne..." class="w-full p-2 border rounded">
  <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
    @foreach($channels as $ch)
      <div class="bg-gray-100 rounded p-2">
        <img src="{{ $ch->logo }}" alt="{{ $ch->name }}" class="h-12 mx-auto">
        <h3 class="text-center mt-2">{{ $ch->name }}</h3>
        <video controls class="w-full mt-2" 
               src="{{ $ch->stream_url }}" 
               type="application/x-mpegURL">
        </video>
      </div>
    @endforeach
  </div>
</div>
```

---

### **Étape 4 : Ajouter le support PWA**

Installe le package Laravel PWA :

```bash
composer require silviolleite/laravel-pwa
```

Configure ton `manifest.json` (nom, icônes, couleurs).
Cela permettra :

* Installation sur téléphone / PC
* Fonctionne même hors ligne pour certaines parties (grâce au cache)

---

### **Étape 6 : Bonus UX**

* Ajoute un mode sombre :

```html
<body class="dark:bg-gray-900 dark:text-white transition duration-300">
```

* Anime les transitions avec Tailwind + Animate.css
* Barre de recherche sticky
* Logo dynamique sentele V2 😎

---

## 🔒 Sécurité simplifiée

* Laravel Sanctum pour protéger les routes API
* Ne jamais exposer les flux IPTV publics → les stocker dans la DB mais **les passer via un proxy Laravel** pour les sécuriser
  Exemple :

  ```php
  Route::get('/stream/{id}', function($id) {
      $ch = Channel::findOrFail($id);
      return redirect($ch->stream_url);
  })->middleware('auth');
  ```

---
