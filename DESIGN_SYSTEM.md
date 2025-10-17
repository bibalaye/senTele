# 🎨 Système de Design Seetaanal IPTV

## Identité visuelle professionnelle inspirée de Canal+

---

## 🎯 Palette de couleurs

### Couleurs principales

#### Noir (Dark)
```css
--dark-50: #f8fafc
--dark-100: #f1f5f9
--dark-200: #e2e8f0
--dark-300: #cbd5e1
--dark-400: #94a3b8
--dark-500: #64748b
--dark-600: #475569
--dark-700: #334155
--dark-800: #1e293b
--dark-900: #0f172a  /* Couleur principale */
--dark-950: #020617
```

#### Vert (Primary)
```css
--primary-50: #f0fdf4
--primary-100: #dcfce7
--primary-200: #bbf7d0
--primary-300: #86efac
--primary-400: #4ade80
--primary-500: #22c55e  /* Couleur principale */
--primary-600: #16a34a
--primary-700: #15803d
--primary-800: #166534
--primary-900: #14532d
--primary-950: #052e16
```

### Couleurs d'accent
- **Vert lime** : `#84cc16`
- **Émeraude** : `#10b981`

---

## 🌓 Mode sombre / Mode clair

### Variables CSS dynamiques

```css
/* Light Mode */
:root {
    --bg-primary: #ffffff
    --bg-secondary: #f8fafc
    --bg-tertiary: #f1f5f9
    --text-primary: #0f172a
    --text-secondary: #475569
    --border-color: #e2e8f0
}

/* Dark Mode */
.dark {
    --bg-primary: #0f172a
    --bg-secondary: #1e293b
    --bg-tertiary: #334155
    --text-primary: #f8fafc
    --text-secondary: #cbd5e1
    --border-color: #334155
}
```

### Toggle Dark Mode

Utilisez le composant `<x-dark-mode-toggle />` dans vos vues.

Le mode est sauvegardé dans `localStorage` et persiste entre les sessions.

---

## 🎨 Composants de style

### Boutons

#### Bouton principal
```html
<button class="btn-primary">
    Choisir ce plan
</button>
```
Style : Dégradé noir vers vert avec effet hover et glow

#### Bouton secondaire
```html
<button class="btn-secondary">
    En savoir plus
</button>
```
Style : Fond sombre avec bordure verte subtile

#### Bouton outline
```html
<button class="btn-outline">
    Annuler
</button>
```
Style : Transparent avec bordure verte, devient vert au hover

---

### Cards

#### Card premium
```html
<div class="card-premium">
    <h3>Titre</h3>
    <p>Contenu</p>
</div>
```
Style : Fond blanc/dark avec ombre et bordure, effet hover avec glow vert

#### Card featured (mise en avant)
```html
<div class="card-premium card-featured">
    <h3>Plan Premium</h3>
    <p>Le plus populaire</p>
</div>
```
Style : Bordure verte avec dégradé subtil en arrière-plan

---

### Plan Cards

#### Structure complète
```html
<div class="plan-card featured">
    <!-- Badge premium (optionnel) -->
    <div class="absolute top-4 right-4">
        <span class="premium-badge">
            POPULAIRE
        </span>
    </div>

    <!-- Header avec dégradé -->
    <div class="plan-card-header">
        <h3>Premium</h3>
        <div>
            <span class="text-5xl">5000</span>
            <span>XOF</span>
        </div>
        <p>/ 30 jours</p>
    </div>

    <!-- Body -->
    <div class="p-8">
        <p>Description</p>
        <ul>
            <li>Fonctionnalité 1</li>
            <li>Fonctionnalité 2</li>
        </ul>
        <button class="btn-primary w-full">
            Choisir ce plan
        </button>
    </div>
</div>
```

---

### Badges

#### Badge success (vert)
```html
<span class="badge-success">Actif</span>
```

#### Badge warning (jaune)
```html
<span class="badge-warning">En attente</span>
```

#### Badge error (rouge)
```html
<span class="badge-error">Expiré</span>
```

#### Premium badge (doré animé)
```html
<span class="premium-badge">
    <svg>...</svg>
    POPULAIRE
</span>
```

---

### Inputs

#### Input moderne
```html
<input type="text" class="input-modern" placeholder="Votre email">
```
Style : Fond adaptatif dark/light avec focus ring vert

---

### Navigation

#### Nav item
```html
<a href="#" class="nav-item">
    Accueil
</a>

<a href="#" class="nav-item active">
    Chaînes
</a>
```
Style : Indicateur vert à gauche pour l'item actif

---

## 🎬 Sections spéciales

### Hero Section (style Canal+)

```html
<div class="hero-section">
    <div class="relative z-10 text-center">
        <h1 class="text-5xl font-bold text-white">
            Titre principal
        </h1>
        <p class="text-xl text-gray-300">
            Sous-titre
        </p>
    </div>
</div>
```

Caractéristiques :
- Dégradé noir vers vert foncé
- Effets radiaux verts en arrière-plan
- Hauteur minimale de 60vh
- Texte centré avec animations

---

### Channel Cards

```html
<div class="channel-card">
    <img src="logo.png" alt="Chaîne">
    <div class="p-4">
        <h3>Nom de la chaîne</h3>
        <p>Catégorie</p>
    </div>
</div>
```

Effets :
- Dégradé noir en arrière-plan
- Bordure verte au hover
- Scale 1.05 au hover
- Glow vert

---

### Stat Cards (Dashboard)

```html
<div class="stat-card">
    <div class="relative z-10">
        <p class="text-sm text-gray-600">Utilisateurs</p>
        <p class="text-3xl font-bold">1,234</p>
    </div>
</div>
```

Effets :
- Cercle vert flou en arrière-plan
- Dégradé subtil
- Hover avec glow

---

## ✨ Animations

### Animations disponibles

```css
/* Fade in */
.animate-fade-in

/* Slide up */
.animate-slide-up

/* Pulse lent */
.animate-pulse-slow

/* Shimmer (loading) */
.shimmer
```

### Skeleton loading

```html
<div class="skeleton h-4 w-full"></div>
<div class="skeleton h-8 w-32 mt-2"></div>
```

---

## 🎯 Effets spéciaux

### Glow vert

```css
/* Ombre avec glow vert */
.shadow-glow-green

/* Glow vert large */
.shadow-glow-green-lg
```

### Glassmorphism

```html
<header class="header-glass">
    <!-- Contenu -->
</header>
```

Effet : Fond flou avec transparence

---

## 📱 Responsive

### Breakpoints Tailwind

- **sm** : 640px
- **md** : 768px
- **lg** : 1024px
- **xl** : 1280px
- **2xl** : 1536px

### Adaptations mobiles

- Hero section : Hauteur réduite à 40vh
- Plan cards : Pas de scale au hover
- Grilles : 1 colonne sur mobile, 2-4 sur desktop

---

## 🎨 Typographie

### Police principale
**Inter** - Google Fonts

### Tailles de texte

```css
text-xs    : 0.75rem  (12px)
text-sm    : 0.875rem (14px)
text-base  : 1rem     (16px)
text-lg    : 1.125rem (18px)
text-xl    : 1.25rem  (20px)
text-2xl   : 1.5rem   (24px)
text-3xl   : 1.875rem (30px)
text-4xl   : 2.25rem  (36px)
text-5xl   : 3rem     (48px)
text-6xl   : 3.75rem  (60px)
```

### Poids de police

```css
font-light     : 300
font-normal    : 400
font-medium    : 500
font-semibold  : 600
font-bold      : 700
font-extrabold : 800
font-black     : 900
```

---

## 🔧 Utilisation

### 1. Importer les styles

Les styles sont automatiquement importés via `resources/css/app.css` :

```css
@import 'tailwindcss';
@import './seetaanal.css';
```

### 2. Activer le dark mode

Ajoutez le toggle dans votre layout :

```blade
<x-dark-mode-toggle />
```

### 3. Utiliser les classes

```html
<!-- Bouton principal -->
<button class="btn-primary">Action</button>

<!-- Card premium -->
<div class="card-premium">
    <h3>Titre</h3>
    <p>Contenu</p>
</div>

<!-- Badge -->
<span class="badge-success">Actif</span>
```

---

## 🎯 Exemples complets

### Page de plans d'abonnement

Voir : `resources/views/livewire/subscription-plans.blade.php`

Caractéristiques :
- Hero section avec dégradé
- Cards de plans avec effet hover
- Badge "POPULAIRE" sur le plan premium
- FAQ avec cards interactives
- Responsive complet

### Dashboard admin

Voir : `resources/views/livewire/admin/dashboard.blade.php`

Caractéristiques :
- Stat cards avec effets
- Tableaux stylisés
- Graphiques (à implémenter)

---

## 🚀 Compilation

Pour compiler les styles :

```bash
npm run dev    # Mode développement
npm run build  # Mode production
```

---

## 📚 Ressources

- **Tailwind CSS** : https://tailwindcss.com
- **Inter Font** : https://fonts.google.com/specimen/Inter
- **Heroicons** : https://heroicons.com

---

**Version** : 1.0.0  
**Date** : 17 janvier 2025  
**Statut** : ✅ Système de design complet
