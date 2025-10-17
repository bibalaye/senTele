# 🔧 Fix OpenSSL Extension - PHP 8.4

## Problème
```
Call to undefined function Illuminate\Encryption\openssl_cipher_iv_length()
```

L'extension OpenSSL n'est pas activée dans PHP 8.4.

## Solution

### Option 1: Via Laragon (Recommandé)

1. **Ouvrir Laragon**
2. **Menu > PHP > Version** - Vérifier que vous utilisez PHP 8.4
3. **Menu > PHP > php.ini**
4. Chercher la ligne: `;extension=openssl`
5. Décommenter (retirer le `;`): `extension=openssl`
6. Sauvegarder
7. **Menu > Laragon > Reload** ou redémarrer Apache

### Option 2: Manuellement

1. **Ouvrir le fichier php.ini**:
   ```
   C:\tools\php84\php.ini
   ```

2. **Chercher** (Ctrl+F): `extension=openssl`

3. **Décommenter** la ligne (retirer le `;` au début):
   ```ini
   ; Avant:
   ;extension=openssl
   
   ; Après:
   extension=openssl
   ```

4. **Vérifier aussi** que le chemin des extensions est correct:
   ```ini
   extension_dir = "C:\tools\php84\ext"
   ```

5. **Sauvegarder** le fichier

6. **Redémarrer Apache** via Laragon

### Option 3: Via ligne de commande

```powershell
# Ouvrir le php.ini
notepad C:\tools\php84\php.ini
```

Puis suivre les étapes de l'option 2.

## Vérification

Après avoir redémarré Apache, vérifiez que OpenSSL est activé:

```powershell
php -m | findstr openssl
```

**Résultat attendu**: `openssl`

Ou vérifiez la version:

```powershell
php -r "echo extension_loaded('openssl') ? 'OpenSSL activé' : 'OpenSSL désactivé';"
```

**Résultat attendu**: `OpenSSL activé`

## Tester Laravel

```powershell
php artisan about
```

Vous devriez voir dans la section "Environment":
```
OpenSSL ......................................................... enabled
```

## Si ça ne fonctionne toujours pas

### Vérifier que le fichier dll existe:

```powershell
dir C:\tools\php84\ext\php_openssl.dll
```

Si le fichier n'existe pas, réinstallez PHP 8.4 via Laragon.

### Vérifier les dépendances:

OpenSSL nécessite les DLL Visual C++ Redistributable. Téléchargez et installez:
- [Visual C++ Redistributable 2015-2022](https://aka.ms/vs/17/release/vc_redist.x64.exe)

## Après la correction

Une fois OpenSSL activé, rechargez la page:
```
http://127.0.0.1:8000/channels
```

Le lecteur HLS devrait maintenant fonctionner correctement !
