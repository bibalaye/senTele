# 🔧 Correction du problème CORS

## 🐛 Problème identifié

**Erreur** : `manifestLoadError` - Erreur réseau CORS

Les flux IPTV (comme France 24) bloquent les requêtes depuis votre domaine local à cause de la politique CORS (Cross-Origin Resource Sharing).

### Pourquoi ça arrive ?
Les serveurs de streaming protègent leurs flux en n'autorisant que certains domaines. Votre `localhost` n'est pas dans leur liste blanche.

## ✅ Solution : Proxy Laravel

J'ai créé un **proxy Laravel** qui :
1. Récupère le flux depuis le serveur IPTV (côté serveur, pas de CORS)
2. Le sert à votre application avec les bons headers CORS
3. Ajoute les headers nécessaires pour que le navigateur accepte le flux

## 📁 Fichiers créés/modifiés

### 1. **Controller du proxy** (`app/Http/Controllers/StreamProxyController.php`)
- Méthode `proxy()` : Récupère et sert le manifest m3u8
- Méthode `segment()` : Récupère et sert les segments vidéo TS
- Ajoute les headers CORS nécessaires

### 2. **Routes** (`routes/web.php`)
```php
Route::get('/stream/proxy/{channel}', [StreamProxyController::class, 'proxy']);
Route::get('/stream/segment', [StreamProxyController::class, 'segment']);
```

### 3. **Vue mise à jour** (`resources/views/livewire/channel-list.blade.php`)
```php
// Avant (URL directe - CORS error)
const streamUrl = @js($selectedChannel->stream_url);

// Après (via proxy - fonctionne)
const streamUrl = @js(route('stream.proxy', ['channel' => $selectedChannel->id]));
```

### 4. **Composant Livewire** (`app/Livewire/ChannelList.php`)
```php
public function getSecureStreamUrl($channelId)
{
    return route('stream.proxy', ['channel' => $channelId]);
}
```

## 🧪 Comment tester

### Test 1 : Vérifier que le proxy fonctionne
```bash
# Ouvrir dans le navigateur (vous devez être connecté)
http://localhost/stream/proxy/1
```

Vous devriez voir du contenu m3u8 (texte) au lieu d'une erreur.

### Test 2 : Page de test comparative
```
http://localhost/test-proxy.html
```

Cette page compare :
- ❌ URL directe (échoue avec CORS)
- ✅ URL via proxy (fonctionne)

### Test 3 : Dans l'application
1. Aller sur `/channels`
2. Cliquer sur une chaîne
3. Le lecteur devrait maintenant fonctionner

## 🔍 Vérifications

### Dans la console du navigateur (F12)
```
✅ 🎬 Initializing HLS player for channel: X
✅ 📺 Stream URL: http://localhost/stream/proxy/X
✅ ✅ HLS.js version: 1.5.15
✅ ✅ Manifest parsed successfully
✅ ▶️ Playing
```

### Si vous voyez encore des erreurs

#### Erreur 401/403
```bash
# Vérifier que vous êtes connecté
# Le proxy nécessite l'authentification
```

#### Erreur 502
```bash
# Le serveur IPTV est peut-être down
# Tester avec une autre chaîne
```

#### Erreur "URL manquante"
```bash
# Vérifier que la chaîne a une stream_url valide
php artisan tinker
>>> App\Models\Channel::find(1)->stream_url
```

## 🚀 Avantages du proxy

### Sécurité
- ✅ Les URLs réelles des flux ne sont pas exposées au client
- ✅ Contrôle d'accès côté serveur
- ✅ Possibilité d'ajouter des tokens temporaires

### Performance
- ✅ Possibilité de mettre en cache les manifests
- ✅ Compression automatique
- ✅ Gestion des erreurs côté serveur

### Compatibilité
- ✅ Résout tous les problèmes CORS
- ✅ Fonctionne avec tous les navigateurs
- ✅ Support mobile complet

## 📊 Flux de données

```
Navigateur → Laravel Proxy → Serveur IPTV
    ↑                              ↓
    └──────── Flux vidéo ──────────┘
```

### Sans proxy (❌ CORS Error)
```
Navigateur ──X──> Serveur IPTV
(Bloqué par CORS)
```

### Avec proxy (✅ Fonctionne)
```
Navigateur → Laravel → Serveur IPTV
           (Pas de CORS)
```

## 🔧 Configuration avancée (optionnel)

### Ajouter un cache pour les manifests
```php
// Dans StreamProxyController.php
$cacheKey = "stream_manifest_{$channel->id}";
return cache()->remember($cacheKey, 5, function() use ($channel) {
    // ... récupérer le flux
});
```

### Ajouter des tokens temporaires
```php
// Générer un token unique par session
$token = hash_hmac('sha256', $channel->id . time(), config('app.key'));
```

### Limiter la bande passante
```php
// Utiliser Laravel Throttle
Route::middleware('throttle:60,1')->group(function() {
    // Routes du proxy
});
```

## 🐛 Debugging

### Activer les logs détaillés
```php
// Dans StreamProxyController.php
\Log::info('Proxy request', [
    'channel' => $channel->id,
    'url' => $channel->stream_url,
    'user' => auth()->id()
]);
```

### Tester avec curl
```bash
# Tester le proxy directement
curl -H "Cookie: laravel_session=..." http://localhost/stream/proxy/1
```

### Vérifier les headers
```javascript
// Dans la console du navigateur
fetch('/stream/proxy/1')
    .then(r => {
        console.log('Headers:', [...r.headers.entries()]);
        return r.text();
    })
    .then(text => console.log('Content:', text.substring(0, 200)));
```

## ✅ Résultat attendu

Après cette correction :
- ✅ Le lecteur s'ouvre quand vous cliquez sur une chaîne
- ✅ La vidéo se charge et démarre automatiquement
- ✅ Pas d'erreur CORS dans la console
- ✅ Lecture fluide du flux HLS

## 📝 Notes importantes

1. **Performance** : Le proxy ajoute une légère latence (quelques ms)
2. **Bande passante** : Tout le trafic vidéo passe par votre serveur
3. **Scaling** : Pour beaucoup d'utilisateurs, considérez un CDN
4. **Légalité** : Assurez-vous d'avoir les droits pour redistribuer les flux

---

**Date** : 17 janvier 2025  
**Statut** : ✅ Implémenté  
**Testé** : En attente de vos tests
