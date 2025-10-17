# 🐛 Debug rapide - Lecteur ne s'ouvre pas

## ✅ Problème corrigé

**Symptôme** : Clic sur une carte de chaîne ne fait rien

**Cause** : Balise HTML mal fermée dans la vue
```html
<!-- ❌ AVANT (ligne 100) -->
<div class="channel-card..." wire:click="selectChannel({{ $channel->id }})"

<!-- ✅ APRÈS -->
<div class="channel-card..." wire:click="selectChannel({{ $channel->id }})">
```

**Solution** : Ajout du `>` manquant pour fermer correctement la balise

## 🧪 Test rapide

### 1. Vérifier qu'il y a des chaînes
```bash
php artisan tinker
>>> App\Models\Channel::count()
```

Si 0, ajouter une chaîne de test :
```bash
php artisan channel:add-test
```

### 2. Tester dans le navigateur
1. Ouvrir la console (F12)
2. Aller sur `/channels`
3. Cliquer sur une chaîne
4. Vérifier les logs dans la console

### 3. Logs attendus
```
🎬 Initializing HLS player for channel: X
📺 Stream URL: https://...
✅ HLS.js version: 1.5.15
✅ HLS.js is supported
✅ Manifest parsed successfully
▶️ Playing
```

## 🔍 Si ça ne marche toujours pas

### Vérifier Livewire
```javascript
// Dans la console du navigateur
console.log(typeof Livewire); // Devrait afficher "object"
```

### Vérifier le wire:click
```javascript
// Inspecter un élément de carte
// Vérifier qu'il a l'attribut wire:click
```

### Vérifier les erreurs
```javascript
// Ouvrir la console et chercher des erreurs en rouge
```

### Vider les caches
```bash
php artisan view:clear
php artisan cache:clear
php artisan config:clear
```

### Recharger la page
- Ctrl + Shift + R (Windows/Linux)
- Cmd + Shift + R (Mac)

## 📋 Checklist de debug

- [ ] Chaîne de test ajoutée (`php artisan channel:add-test`)
- [ ] Page rechargée (Ctrl + Shift + R)
- [ ] Console ouverte (F12)
- [ ] Pas d'erreurs JavaScript
- [ ] Livewire chargé
- [ ] HLS.js chargé
- [ ] Clic sur la carte déclenche l'événement

## 🎯 Comportement attendu

1. **Clic sur la carte** → Modal s'ouvre immédiatement
2. **Spinner** → Visible pendant 1-2 secondes
3. **Vidéo** → Démarre automatiquement
4. **Contrôles** → Visibles et fonctionnels

## 🚨 Erreurs communes

### "Livewire is not defined"
```bash
# Vérifier que Livewire est installé
composer show livewire/livewire
```

### "Hls is not defined"
Vérifier dans `resources/views/partials/head.blade.php` :
```html
<script src="https://cdn.jsdelivr.net/npm/hls.js@1.5.15/dist/hls.min.js"></script>
```

### "Cannot read property 'call' of undefined"
```bash
# Reconstruire les assets
npm run build
```

### Modal ne s'ouvre pas
Vérifier dans `app/Livewire/ChannelList.php` que la méthode `selectChannel` existe

---

**Correction appliquée** : 17 janvier 2025  
**Statut** : ✅ Corrigé
