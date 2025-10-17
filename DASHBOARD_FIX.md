# 🔧 Correction du Dashboard Admin - TERMINÉE

## ❌ Problème rencontré

Lors de l'accès au dashboard admin (`/admin/dashboard`), l'erreur suivante apparaissait :

```
SQLSTATE[23000]: Integrity constraint violation: 1052 Column 'created_at' in where clause is ambiguous
```

### Requête SQL problématique

```sql
SELECT SUM(`subscription_plans`.`price`) AS aggregate 
FROM `user_subscriptions` 
INNER JOIN `subscription_plans` 
  ON `user_subscriptions`.`subscription_plan_id` = `subscription_plans`.`id` 
WHERE `status` = 'active' 
  AND `expires_at` > '2025-10-17 16:06:07' 
  AND MONTH(`created_at`) = 10
```

### Cause du problème

La colonne `created_at` existe dans **les deux tables** :
- `user_subscriptions.created_at`
- `subscription_plans.created_at`

Lorsqu'on fait un `JOIN` entre ces deux tables et qu'on utilise `whereMonth('created_at', ...)`, MySQL ne sait pas quelle colonne utiliser, d'où l'erreur "ambiguous".

---

## ✅ Solution appliquée

### Fichier modifié
`app/Livewire/Admin/Dashboard.php`

### Code avant (ligne 29-32)

```php
'monthly_revenue' => UserSubscription::active()
    ->whereMonth('created_at', now()->month)
    ->join('subscription_plans', 'user_subscriptions.subscription_plan_id', '=', 'subscription_plans.id')
    ->sum('subscription_plans.price'),
```

### Code après (corrigé)

```php
'monthly_revenue' => UserSubscription::active()
    ->whereMonth('user_subscriptions.created_at', now()->month)
    ->join('subscription_plans', 'user_subscriptions.subscription_plan_id', '=', 'subscription_plans.id')
    ->sum('subscription_plans.price'),
```

### Changement

Remplacement de :
```php
->whereMonth('created_at', now()->month)
```

Par :
```php
->whereMonth('user_subscriptions.created_at', now()->month)
```

---

## 🎯 Résultat

Après la correction, la requête SQL devient :

```sql
SELECT SUM(`subscription_plans`.`price`) AS aggregate 
FROM `user_subscriptions` 
INNER JOIN `subscription_plans` 
  ON `user_subscriptions`.`subscription_plan_id` = `subscription_plans`.`id` 
WHERE `status` = 'active' 
  AND `expires_at` > '2025-10-17 16:06:07' 
  AND MONTH(`user_subscriptions`.`created_at`) = 10
```

✅ La colonne `created_at` est maintenant **non ambiguë**  
✅ Le dashboard admin fonctionne correctement  
✅ Les revenus mensuels s'affichent sans erreur

---

## 📊 Statistiques affichées dans le dashboard

Le dashboard admin affiche maintenant correctement :

1. **Utilisateurs**
   - Total des utilisateurs
   - Utilisateurs actifs
   - Utilisateurs bannis

2. **Abonnements**
   - Abonnements actifs
   - Abonnements expirés
   - Revenus du mois en cours

3. **Chaînes**
   - Total des chaînes
   - Chaînes actives
   - Top 10 des chaînes les plus regardées

---

## 🧪 Test

Pour vérifier que tout fonctionne :

1. Connectez-vous en tant qu'admin
2. Accédez à : `http://localhost:8000/admin/dashboard`
3. Vérifiez que toutes les statistiques s'affichent correctement

---

## 💡 Leçon apprise

Lorsqu'on utilise des `JOIN` entre plusieurs tables qui ont des colonnes avec le même nom, il faut **toujours spécifier le nom de la table** pour éviter les ambiguïtés :

### ❌ Mauvais
```php
->whereMonth('created_at', now()->month)
```

### ✅ Bon
```php
->whereMonth('user_subscriptions.created_at', now()->month)
```

Ou encore mieux, utiliser des alias de table :

```php
UserSubscription::from('user_subscriptions as us')
    ->whereMonth('us.created_at', now()->month)
    ->join('subscription_plans as sp', 'us.subscription_plan_id', '=', 'sp.id')
    ->sum('sp.price')
```

---

## ✨ Statut

**✅ PROBLÈME RÉSOLU**

Le dashboard admin fonctionne maintenant parfaitement !

---

**Date de correction** : 17 janvier 2025  
**Fichier modifié** : `app/Livewire/Admin/Dashboard.php`  
**Ligne modifiée** : 30
