# 🎬 Guide de Test - Lecteur HLS & PWA

## 🚀 Démarrage rapide

### 1. Compiler les assets

```bash
npm install
npm run dev
```

Ou pour la production :

```bash
npm run build
```

### 2. Démarrer le serveur

```bash
php artisan serve
```

### 3. Ajouter une chaîne de test

Ouvrir `php artisan tinker` et exécuter :

```php
Channel::create([
    'name' => 'Big Buck Bunny (Test)',
    'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c5/Big_buck_bunny_poster_big.jpg/220px-Big_buck_bunny_poster_big.jpg',
    'country' => 'International',
    'category' => 'Test',
    'stream_url' => 'https://test-streams.mux.dev/x36xhzz/x36xhzz.m3u8',
    'is_active' => true,
]);
```

**Autres flux de test gratuits** :

```php
// Arte (France)
Channel::create([
    'name' => 'Arte',
    'logo' => 'https://www.arte.tv/favicon.ico',
    'country' => 'France',
    'category' => 'Culture',
    'stream_url' => 'https://artesimulcast.akamaized.net/hls/live/2030993/artelive_fr/master.m3u8',
    'is_active' => true,
]);

// France 24 (News)
Channel::create([
    'name' => 'France 24',
    'logo' => 'https://www.france24.com/favicon.ico',
    'country' => 'France',
    'category' => 'News',
    'stream_url' => 'https://static.france24.com/live/F24_FR_HI_HLS/live_web.m3u8',
    'is_active' => true,
]);
```

---

## ✅ Checklist de test

### Lecteur HLS

- [ ] Cliquer sur une chaîne ouvre le lecteur
- [ ] La vidéo démarre automatiquement (ou après un clic)
- [ ] Les contrôles vidéo fonctionnent (play, pause, volume)
- [ ] Le plein écran fonctionne
- [ ] Fermer avec le bouton X fonctionne
- [ ] Fermer avec Escape fonctionne
- [ ] Le spinner de chargement s'affiche puis disparaît
- [ ] En cas d'erreur, un message s'affiche avec bouton "Réessayer"

### PWA

- [ ] Le bouton "Installer l'app" apparaît (si pas déjà installé)
- [ ] Cliquer sur "Installer l'app" lance l'installation
- [ ] L'app s'installe sur l'écran d'accueil
- [ ] Ouvrir l'app installée fonctionne en mode standalone
- [ ] Le Service Worker est enregistré (vérifier dans DevTools)

### Favoris

- [ ] Cliquer sur le cœur ajoute aux favoris
- [ ] Cliquer à nouveau retire des favoris
- [ ] L'icône change d'état (vide → plein)
- [ ] Les favoris persistent après rechargement

### Recherche & Filtres

- [ ] La recherche filtre les chaînes en temps réel
- [ ] Les filtres par catégorie fonctionnent
- [ ] "Tout" affiche toutes les chaînes
- [ ] La recherche fonctionne sur nom, catégorie, pays

### Responsive

- [ ] Sur mobile : layout adapté
- [ ] Sur tablette : layout adapté
- [ ] Sur desktop : layout adapté
- [ ] Le lecteur s'adapte à toutes les tailles

---

## 🔍 Debugging

### Ouvrir la console du navigateur

**Chrome/Edge** : `F12` ou `Ctrl+Shift+I`
**Firefox** : `F12` ou `Ctrl+Shift+K`
**Safari** : `Cmd+Option+I`

### Vérifier HLS.js

Dans la console :

```javascript
// Vérifier que HLS.js est chargé
typeof Hls
// Devrait retourner: "function"

// Vérifier le support
Hls.isSupported()
// Devrait retourner: true (sauf Safari qui utilise le natif)

// Vérifier le player
window.sentelePlayer
// Devrait retourner: SentelePlayer {hls: null, videoElement: null, ...}
```

### Vérifier le Service Worker

Dans DevTools → Application → Service Workers :
- Status : "activated and is running"
- Scope : "/"

### Vérifier le Manifest

Dans DevTools → Application → Manifest :
- Toutes les propriétés doivent être affichées
- Les icônes doivent être listées (même si les fichiers n'existent pas encore)

### Vérifier le Cache

Dans DevTools → Application → Cache Storage :
- `sentele-v2-1.0.0-static`
- `sentele-v2-1.0.0-dynamic`
- `sentele-v2-1.0.0-images`

---

## 🎮 Test sur différents appareils

### Android

1. Ouvrir Chrome
2. Aller sur le site
3. Menu → "Installer l'application"
4. Tester le lecteur
5. Tester en mode avion (offline)

### iPhone/iPad

1. Ouvrir Safari
2. Aller sur le site
3. Partager → "Sur l'écran d'accueil"
4. Tester le lecteur (support natif HLS)

### Xbox

1. Ouvrir Microsoft Edge
2. Aller sur le site
3. Tester le lecteur avec la manette
4. Ajouter aux favoris pour accès rapide

### PlayStation

1. Ouvrir le navigateur PS
2. Aller sur le site
3. Tester le lecteur avec la manette
4. Ajouter aux favoris

---

## 🐛 Problèmes courants

### "Hls is not defined"

**Solution** : Vérifier que HLS.js est bien chargé dans `head.blade.php`

```html
<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
```

### "Cannot read property 'init' of undefined"

**Solution** : Compiler les assets

```bash
npm run dev
```

### Le lecteur ne démarre pas

**Causes possibles** :
1. URL du stream invalide
2. CORS bloqué par le serveur de streaming
3. Format non HLS (.m3u8)

**Solution** : Vérifier l'URL dans la console et tester avec un flux de test

### La PWA ne s'installe pas

**Causes possibles** :
1. Pas en HTTPS (requis pour PWA)
2. Manifest.json invalide
3. Service Worker non enregistré

**Solution** :
- Utiliser `php artisan serve` avec HTTPS
- Ou déployer sur un serveur HTTPS
- Vérifier DevTools → Application

### Erreur CORS

```
Access to XMLHttpRequest at 'https://...' from origin 'http://localhost:8000' has been blocked by CORS
```

**Solution** : Le serveur de streaming doit autoriser votre domaine. Pour les tests, utiliser les flux de test fournis ci-dessus.

---

## 📊 Performance

### Lighthouse Audit

1. DevTools → Lighthouse
2. Cocher "Progressive Web App"
3. Cliquer "Generate report"

**Score attendu** :
- PWA : 90-100
- Performance : 80-100
- Accessibility : 90-100
- Best Practices : 90-100

### Optimisations

Si les scores sont bas :

1. **Performance** :
   - Activer la compression Gzip/Brotli
   - Utiliser un CDN
   - Optimiser les images

2. **PWA** :
   - Vérifier que toutes les icônes existent
   - Vérifier le Service Worker
   - Ajouter un splash screen

3. **Accessibility** :
   - Ajouter des labels ARIA
   - Vérifier le contraste des couleurs
   - Tester au clavier

---

## ✨ Fonctionnalités avancées à tester

### Mode Picture-in-Picture

```javascript
// Dans la console, quand une vidéo joue
document.getElementById('main-video-player').requestPictureInPicture();
```

### Notifications

```javascript
// Tester les notifications
window.pwaManager.showNotification('Test', 'Ceci est un test');
```

### Mode offline

1. Ouvrir DevTools → Network
2. Cocher "Offline"
3. Recharger la page
4. Devrait afficher la page offline

---

## 🎉 Succès !

Si tous les tests passent, vous avez :
- ✅ Un lecteur HLS fonctionnel sur tous les appareils
- ✅ Une PWA installable
- ✅ Un système de cache intelligent
- ✅ Une interface responsive et moderne

**Prêt pour ajouter vos vraies chaînes IPTV !** 🚀
