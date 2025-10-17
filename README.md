# SenTele - Plateforme IPTV

Une plateforme IPTV moderne construite avec Laravel 11, Livewire 3 et Flux UI.

## Fonctionnalités

- 📺 Streaming de chaînes IPTV en direct
- 💳 Système d'abonnement avec paiements intégrés
- 👤 Gestion des utilisateurs et authentification
- 🎨 Interface moderne avec mode sombre
- 📱 Progressive Web App (PWA)
- 🔐 Authentification à deux facteurs
- 👨‍💼 Panel d'administration complet
- 📊 Historique de visionnage
- ⭐ Système de favoris

## Technologies

- **Backend**: Laravel 11
- **Frontend**: Livewire 3, Flux UI, TailwindCSS
- **Base de données**: MySQL/PostgreSQL
- **Streaming**: HLS.js pour la lecture vidéo

## Installation

### Prérequis

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL/PostgreSQL

### Étapes

1. Cloner le repository
```bash
git clone https://github.com/votre-username/sentele.git
cd sentele
```

2. Installer les dépendances
```bash
composer install
npm install
```

3. Configurer l'environnement
```bash
cp .env.example .env
php artisan key:generate
```

4. Configurer la base de données dans `.env`

5. Exécuter les migrations
```bash
php artisan migrate --seed
```

6. Compiler les assets
```bash
npm run build
```

7. Démarrer le serveur
```bash
php artisan serve
```

## Configuration pour Production

### Variables d'environnement importantes

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-domaine.com

DB_CONNECTION=mysql
DB_HOST=votre-host
DB_DATABASE=votre-db
DB_USERNAME=votre-user
DB_PASSWORD=votre-password

# Configuration des paiements
STRIPE_KEY=votre-stripe-key
STRIPE_SECRET=votre-stripe-secret
```

## Déploiement sur Render

Le projet inclut un fichier `render.yaml` pour un déploiement facile sur Render.

## Licence

Propriétaire - Tous droits réservés
