# 🔧 Dépannage HLS.js

## Problème: Le lecteur ne se charge pas

### Étape 1: Vérifier que HLS.js est chargé

Ouvrez la console du navigateur (F12) et tapez:
```javascript
console.log(typeof Hls, Hls?.version);
```

**Résultat attendu**: `function 1.5.15`

**Si "undefined"**:
- Le CDN jsdelivr.net est bloqué ou inaccessible
- Vérifiez votre connexion internet
- Essayez de recharger la page

### Étape 2: Tester avec la page de test

1. Ouvrez: `http://localhost/test-hls.html`
2. Utilisez le flux de test: `https://test-streams.mux.dev/x36xhzz/x36xhzz.m3u8`
3. Cliquez sur "Charger le flux"

**Si ça fonctionne**: Le problème vient de l'intégration Livewire
**Si ça ne fonctionne pas**: Le problème vient de HLS.js ou du navigateur

### Étape 3: Vérifier l'URL du flux

Dans la console, quand vous cliquez sur une chaîne, vous devriez voir:
```
📺 Stream URL: https://...
```

**Vérifications**:
- L'URL doit se terminer par `.m3u8`
- L'URL doit être accessible (testez-la dans un nouvel onglet)
- L'URL ne doit pas avoir d'erreur CORS

### Étape 4: Vérifier les erreurs réseau

Dans l'onglet "Network" (Réseau) de la console:
1. Filtrez par "m3u8"
2. Cliquez sur une chaîne
3. Vérifiez que la requête vers le .m3u8 réussit (status 200)

**Erreurs courantes**:
- **404**: L'URL n'existe pas
- **403**: Accès refusé (problème d'authentification)
- **CORS**: Le serveur bloque les requêtes cross-origin

## Problème: Le lecteur se charge mais ne joue pas

### Vérifier le format du flux

Dans la console, après "Manifest parsed successfully", vérifiez:
```javascript
console.log(window.currentHls.levels);
```

Cela affiche les qualités disponibles. Si vide ou erreur, le flux n'est pas valide.

### Vérifier les codecs

Certains codecs ne sont pas supportés par tous les navigateurs:
- **H.264**: Supporté partout ✅
- **H.265/HEVC**: Supporté uniquement sur Safari/iOS ⚠️
- **VP9**: Supporté sur Chrome/Firefox ⚠️

### Tester sur un autre navigateur

- Chrome/Edge: Meilleur support HLS.js
- Firefox: Bon support
- Safari: Support natif HLS (pas besoin de HLS.js)

## Problème: "Autoplay prevented"

C'est **normal** ! Les navigateurs bloquent l'autoplay par défaut.

**Solutions**:
1. L'utilisateur doit cliquer sur play manuellement
2. Ou interagir avec la page avant (clic sur la chaîne compte comme interaction)

## Problème: Le lecteur se fige ou buffer constamment

### Vérifier la bande passante

Le flux nécessite une connexion stable. Testez votre vitesse:
```
https://fast.com
```

### Réduire la qualité

Dans `player.js`, ajustez les paramètres:
```javascript
maxBufferLength: 10,  // Au lieu de 30
backBufferLength: 30, // Au lieu de 90
```

## Commandes utiles pour le debugging

### Dans la console du navigateur:

```javascript
// Vérifier HLS.js
console.log('HLS.js:', typeof Hls, Hls?.version);

// Vérifier le player manager
console.log('Player:', window.sentelePlayer);

// Vérifier l'instance HLS actuelle
console.log('Current HLS:', window.currentHls);

// Vérifier les niveaux de qualité
console.log('Levels:', window.currentHls?.levels);

// Forcer une qualité spécifique (0 = la plus basse)
window.currentHls.currentLevel = 0;

// Activer les logs détaillés
window.currentHls.config.debug = true;
```

## Tester un flux manuellement

```javascript
const video = document.getElementById('main-video-player');
const hls = new Hls({ debug: true });
hls.loadSource('VOTRE_URL.m3u8');
hls.attachMedia(video);
hls.on(Hls.Events.MANIFEST_PARSED, () => {
    console.log('✅ Manifest OK');
    video.play();
});
hls.on(Hls.Events.ERROR, (e, data) => {
    console.error('❌ Error:', data);
});
```

## Flux de test publics

Pour tester si HLS.js fonctionne correctement:

```
https://test-streams.mux.dev/x36xhzz/x36xhzz.m3u8
https://demo.unified-streaming.com/k8s/features/stable/video/tears-of-steel/tears-of-steel.ism/.m3u8
https://cph-p2p-msl.akamaized.net/hls/live/2000341/test/master.m3u8
```

## Besoin d'aide ?

1. Ouvrez la console (F12)
2. Reproduisez le problème
3. Copiez tous les logs (clic droit > Save as...)
4. Partagez les logs avec les détails du problème
