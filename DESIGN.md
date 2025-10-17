# 🎨 Design senTele V2

## Interface des Chaînes

### 📺 Carte de Chaîne

Chaque carte affiche :

```
┌─────────────────────────────────┐
│ [LIVE]                      [❤] │
│                                 │
│          [LOGO CHAÎNE]          │
│                                 │
│         [▶ PLAY BUTTON]         │
├─────────────────────────────────┤
│ Titre de la Chaîne              │
│ [📹 Catégorie]                  │
│ 📍 Pays                         │
└─────────────────────────────────┘
```

### 🎯 Éléments Visuels

**En-tête de la carte :**
- Badge "En direct" rouge avec point pulsant (top-left)
- Bouton favori (cœur) en overlay (top-right)
- Logo de la chaîne centré avec effet zoom au survol

**Overlay au survol :**
- Fond noir semi-transparent
- Bouton play blanc central
- Gradient du bas vers le haut

**Informations :**
- Titre en blanc, bold, 1 ligne max
- Badge catégorie avec icône vidéo
- Pays avec icône de localisation

### 🎨 Couleurs

```css
Fond carte       : zinc-900/50 → zinc-900 (hover)
Fond logo        : gradient zinc-800 → zinc-900
Badge Live       : red-600 (#DC2626)
Bouton Play      : white
Titre            : white → red-400 (hover)
Badge catégorie  : zinc-800 + gray-300
Pays             : gray-400
```

### 📐 Dimensions

```css
Ratio            : 16:9 (aspect-video)
Padding logo     : 1.5rem (p-6)
Padding info     : 1rem (p-4)
Border radius    : 0.75rem (rounded-xl)
Gap grid         : 1rem → 1.5rem (gap-4 → gap-6)
```

### 🎭 Animations

```css
Hover carte      : scale(1.02) + shadow-2xl
Hover logo       : scale(1.1)
Hover play       : scale(0.75 → 1)
Hover favori     : scale(1.1)
Hover titre      : color red-400
Transition       : 300ms ease
```

### 📱 Grid Responsive

```css
Mobile (< 640px)   : 1 colonne
Tablet (640-1024)  : 2 colonnes
Desktop (1024-1280): 3 colonnes
Large (1280-1536)  : 4 colonnes
XL (> 1536px)      : 5 colonnes
```

---

## Lecteur Vidéo

### 🎬 Layout Fullscreen

```
┌─────────────────────────────────────────┐
│                                    [X]  │
│                                         │
│          [VIDEO PLAYER]                 │
│                                         │
├─────────────────────────────────────────┤
│ [LIVE] Titre • Catégorie • Pays    [❤] │
│        📹 HLS • Qualité adaptative      │
└─────────────────────────────────────────┘
```

### 🎨 Éléments

- Fond noir pur (#000)
- Bouton fermer (top-right)
- Vidéo centrée
- Barre d'info (zinc-950)
- Badge Live rouge
- Métadonnées en gris
- Bouton favori

---

## Navigation

### 🧭 Header

```
senTele  Accueil En direct Favoris    [🔍] [Installer] [A]
```

**Éléments :**
- Logo "senTele" bold blanc
- Navigation horizontale
- Recherche intégrée
- Bouton installer
- Avatar utilisateur

### 🏷️ Filtres

```
[Tout] [News] [Sports] [Science] [Business] [Lifestyle] [Food]
```

**Style :**
- Pills horizontales
- Fond blanc (actif) / zinc-900 (inactif)
- Scroll horizontal sur mobile

---

## Icônes Utilisées

```
📺 Chaîne par défaut
🔴 Live / En direct
▶️  Play
❤️  Favori (plein)
🤍 Favori (vide)
📹 Catégorie vidéo
📍 Localisation / Pays
🔍 Recherche
```

---

## Typographie

```css
Logo         : 1.5rem (text-2xl) bold
Titre section: 1.875rem (text-3xl) bold
Titre carte  : 1rem (text-base) bold
Catégorie    : 0.75rem (text-xs) semibold
Pays         : 0.875rem (text-sm) medium
Badge        : 0.625rem (text-[10px]) bold uppercase
```

---

## Accessibilité

✅ Contraste AAA (blanc sur noir)
✅ Focus visible sur tous les boutons
✅ Alt text sur toutes les images
✅ Keyboard navigation (Échap)
✅ ARIA labels
✅ Lazy loading images

---

Design inspiré de Canal+, Netflix, Disney+ 🏆
