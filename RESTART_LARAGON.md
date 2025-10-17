# 🔄 Redémarrer Laragon

## ✅ Extension OpenSSL activée !

L'extension OpenSSL a été activée dans php.ini.

## Prochaine étape : Redémarrer Apache

### Option 1 : Via l'interface Laragon

1. **Ouvrir Laragon**
2. **Clic droit** sur l'icône Laragon dans la barre des tâches
3. **Menu > Apache > Reload**

Ou simplement :

1. **Clic sur "Stop All"**
2. **Clic sur "Start All"**

### Option 2 : Via ligne de commande

Si Laragon est dans le PATH :

```powershell
# Arrêter
laragon stop

# Démarrer
laragon start
```

### Option 3 : Redémarrer manuellement Apache

Si vous utilisez Apache directement :

```powershell
# Arrêter Apache
net stop Apache2.4

# Démarrer Apache
net start Apache2.4
```

## Vérification

Après le redémarrage, vérifiez que OpenSSL est bien chargé :

```powershell
php -m | findstr openssl
```

**Résultat attendu** : `openssl`

## Tester l'application

Une fois Apache redémarré, ouvrez :

```
http://127.0.0.1:8000/channels
```

ou

```
http://sentele.test/channels
```

L'erreur `openssl_cipher_iv_length()` devrait avoir disparu !

## Si l'erreur persiste

1. Vérifiez que vous avez bien redémarré Apache (pas seulement rechargé la page)
2. Vérifiez la version de PHP utilisée :
   ```powershell
   php -v
   ```
3. Vérifiez que le bon php.ini est chargé :
   ```powershell
   php --ini
   ```
4. Videz le cache Laravel :
   ```powershell
   php artisan config:clear
   php artisan cache:clear
   ```
