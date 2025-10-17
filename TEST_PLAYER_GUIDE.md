# 🧪 Guide de test du lecteur HLS

## 🚀 Démarrage rapide

### 1. Ajouter une chaîne de test
```bash
php artisan channel:add-test
```

Cette commande crée automatiquement une chaîne de test avec :
- ✅ Nom : "Test Channel - Big Buck Bunny"
- ✅ Flux HLS public fonctionnel
- ✅ Logo
- ✅ Associée au plan gratuit

### 2. Tester le lecteur
1. Ouvrir votre navigateur
2. Aller sur `http://localhost/channels`
3. Cliquer sur la chaîne "Test Channel - Big Buck Bunny"
4. Le lecteur devrait s'ouvrir et lire automatiquement

## 🔍 Vérifications

### Console du navigateur (F12)
Vous devriez voir ces logs :
```
🎬 Initializing HLS player for channel: X
📺 Stream URL: https://test-streams.mux.dev/...
✅ HLS.js version: 1.5.15
✅ HLS.js is supported
✅ Manifest parsed successfully
▶️ Playing
```

### Comportement attendu
- ✅ Modal s'ouvre avec le lecteur
- ✅ Spinner de chargement pendant 1-2 secondes
- ✅ Vidéo démarre automatiquement
- ✅ Contrôles vidéo fonctionnels (play, pause, volume, plein écran)
- ✅ Badge "En direct" visible
- ✅ Informations de la chaîne affichées
- ✅ Bouton fermer (X) fonctionne
- ✅ Touche Escape ferme le lecteur

## 🐛 Résolution des problèmes

### Le lecteur ne s'ouvre pas
```bash
# Vérifier que la chaîne existe
php artisan tinker
>>> App\Models\Channel::where('name', 'LIKE', '%Test%')->count()
```

### Erreur "HLS.js not loaded"
1. Vérifier que `resources/views/partials/head.blade.php` contient :
```html
<script src="https://cdn.jsdelivr.net/npm/hls.js@1.5.15/dist/hls.min.js"></script>
```

2. Vider le cache :
```bash
php artisan view:clear
php artisan cache:clear
```

### Erreur réseau / CORS
- Vérifier votre connexion internet
- Tester l'URL directement dans le navigateur
- Essayer une autre URL de test

### La vidéo ne démarre pas
1. Vérifier l'autoplay dans le navigateur
2. Cliquer manuellement sur play
3. Vérifier la console pour les erreurs

## 📺 Autres flux de test

### Ajouter manuellement d'autres chaînes
```bash
php artisan tinker
```

```php
// Apple Test Stream
$channel = App\Models\Channel::create([
    'name' => 'Apple Test Stream',
    'stream_url' => 'https://devstreaming-cdn.apple.com/videos/streaming/examples/img_bipbop_adv_example_fmp4/master.m3u8',
    'category' => 'Test',
    'country' => 'USA',
    'is_active' => true,
]);
$channel->subscriptionPlans()->attach(1); // Plan gratuit

// Sintel (Blender)
$channel = App\Models\Channel::create([
    'name' => 'Sintel - Blender Movie',
    'stream_url' => 'https://bitdash-a.akamaihd.net/content/sintel/hls/playlist.m3u8',
    'category' => 'Films',
    'country' => 'International',
    'is_active' => true,
]);
$channel->subscriptionPlans()->attach(1);
```

## ✅ Checklist de test

### Fonctionnalités de base
- [ ] Ouverture du lecteur
- [ ] Chargement du flux
- [ ] Lecture automatique
- [ ] Contrôles vidéo
- [ ] Fermeture du lecteur
- [ ] Touche Escape

### Fonctionnalités avancées
- [ ] Plein écran
- [ ] Contrôle du volume
- [ ] Pause / Reprise
- [ ] Changement de chaîne
- [ ] Favoris (si connecté)

### Responsive
- [ ] Desktop (1920x1080)
- [ ] Tablet (768x1024)
- [ ] Mobile (375x667)

### Navigateurs
- [ ] Chrome
- [ ] Firefox
- [ ] Edge
- [ ] Safari (si disponible)

## 📊 Métriques de performance

### Temps de chargement attendus
- Ouverture du modal : < 100ms
- Chargement du manifest : 1-3 secondes
- Démarrage de la lecture : 2-5 secondes

### Utilisation mémoire
- Lecteur inactif : ~50 MB
- Lecteur actif : ~100-150 MB

## 🎯 Prochaines étapes

Une fois le test réussi :
1. ✅ Importer vos vraies chaînes IPTV
2. ✅ Configurer les plans d'abonnement
3. ✅ Tester avec différents types de flux
4. ✅ Optimiser les performances
5. ✅ Déployer en production

---

**Besoin d'aide ?**
- Vérifier `PLAYER_FIX.md` pour les détails techniques
- Consulter les logs dans la console du navigateur
- Tester avec VLC pour valider les URLs de flux
