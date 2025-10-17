# 🏗️ Architecture Seetaanal IPTV

## 📊 Vue d'ensemble

```
┌─────────────────────────────────────────────────────────────┐
│                    SEETAANAL IPTV                           │
│              Plateforme de streaming IPTV                   │
└─────────────────────────────────────────────────────────────┘
                            │
        ┌───────────────────┼───────────────────┐
        │                   │                   │
   ┌────▼────┐         ┌────▼────┐        ┌────▼────┐
   │  Users  │         │  Admin  │        │   API   │
   │Interface│         │Dashboard│        │ (Future)│
   └────┬────┘         └────┬────┘        └─────────┘
        │                   │
        └───────────┬───────┘
                    │
        ┌───────────▼───────────┐
        │   Laravel Backend     │
        │   (Livewire + Flux)   │
        └───────────┬───────────┘
                    │
        ┌───────────▼───────────┐
        │   Database (MySQL)    │
        └───────────────────────┘
```

---

## 🗂️ Structure de la base de données

```
┌──────────────┐         ┌─────────────────────┐
│    users     │────────▶│ user_subscriptions  │
│              │         │                     │
│ - id         │         │ - user_id           │
│ - name       │         │ - plan_id           │
│ - email      │         │ - starts_at         │
│ - is_admin   │         │ - expires_at        │
│ - is_banned  │         │ - status            │
└──────┬───────┘         └──────────┬──────────┘
       │                            │
       │                            │
       │                  ┌─────────▼──────────┐
       │                  │ subscription_plans │
       │                  │                    │
       │                  │ - id               │
       │                  │ - name             │
       │                  │ - price            │
       │                  │ - duration_days    │
       │                  │ - max_devices      │
       │                  └─────────┬──────────┘
       │                            │
       │                            │
       │                  ┌─────────▼──────────────┐
       │                  │ channel_subscription_  │
       │                  │        plan            │
       │                  │                        │
       │                  │ - channel_id           │
       │                  │ - subscription_plan_id │
       │                  └─────────┬──────────────┘
       │                            │
       │                  ┌─────────▼──────────┐
       ├─────────────────▶│     channels       │
       │                  │                    │
       │                  │ - id               │
       │                  │ - name             │
       │                  │ - stream_url       │
       │                  │ - category         │
       │                  │ - is_active        │
       │                  │ - views_count      │
       │                  └─────────┬──────────┘
       │                            │
       │                            │
       ├──────────────┐             │
       │              │             │
┌──────▼──────┐  ┌────▼─────┐      │
│  favorites  │  │  watch_  │      │
│             │  │ history  │      │
│ - user_id   │  │          │      │
│ - channel_id│  │ - user_id│      │
└─────────────┘  │ - channel│      │
                 │   _id    │      │
                 │ - watched│      │
                 │   _at    │      │
                 └──────────┘      │
                                   │
                        ┌──────────▼──────────┐
                        │    promo_codes      │
                        │                     │
                        │ - code              │
                        │ - type              │
                        │ - value             │
                        │ - max_uses          │
                        │ - expires_at        │
                        └─────────────────────┘
```

---

## 🎯 Flux de données

### Flux d'authentification

```
User
  │
  ├─▶ Login
  │     │
  │     ├─▶ Check credentials
  │     │     │
  │     │     ├─▶ Valid ──▶ Check is_banned
  │     │     │                    │
  │     │     │                    ├─▶ Not banned ──▶ Login success
  │     │     │                    │
  │     │     │                    └─▶ Banned ──▶ Reject
  │     │     │
  │     │     └─▶ Invalid ──▶ Error
  │     │
  │     └─▶ Redirect to dashboard
  │
  └─▶ Access protected route
        │
        ├─▶ Middleware: auth
        │     │
        │     └─▶ Authenticated? ──▶ Continue
        │
        ├─▶ Middleware: subscription
        │     │
        │     └─▶ Has active subscription? ──▶ Continue
        │
        └─▶ Middleware: admin (if admin route)
              │
              └─▶ Is admin? ──▶ Continue
```

### Flux d'abonnement

```
User
  │
  ├─▶ Browse subscription plans
  │     │
  │     └─▶ Select plan
  │           │
  │           ├─▶ Free plan
  │           │     │
  │           │     └─▶ Activate immediately
  │           │
  │           └─▶ Paid plan
  │                 │
  │                 ├─▶ Choose payment method
  │                 │     │
  │                 │     ├─▶ Wave Money
  │                 │     ├─▶ Orange Money
  │                 │     ├─▶ Free Money
  │                 │     └─▶ Card (Stripe)
  │                 │
  │                 ├─▶ Process payment
  │                 │     │
  │                 │     ├─▶ Success ──▶ Activate subscription
  │                 │     │
  │                 │     └─▶ Failed ──▶ Show error
  │                 │
  │                 └─▶ Receive confirmation
  │
  └─▶ Access channels based on subscription
```

### Flux de visionnage

```
User
  │
  ├─▶ Browse channels
  │     │
  │     ├─▶ Filter by category
  │     │
  │     └─▶ Search by name
  │
  ├─▶ Select channel
  │     │
  │     ├─▶ Check subscription access
  │     │     │
  │     │     ├─▶ Has access ──▶ Load player
  │     │     │                    │
  │     │     │                    ├─▶ HLS.js initialization
  │     │     │                    │
  │     │     │                    ├─▶ Load M3U8 stream
  │     │     │                    │
  │     │     │                    ├─▶ Start playback
  │     │     │                    │
  │     │     │                    └─▶ Track watch history
  │     │     │
  │     │     └─▶ No access ──▶ Show upgrade prompt
  │     │
  │     └─▶ Add to favorites (optional)
  │
  └─▶ Continue watching or browse
```

---

## 🔧 Architecture des composants

### Frontend (Livewire)

```
┌─────────────────────────────────────────┐
│         User Components                 │
├─────────────────────────────────────────┤
│                                         │
│  ChannelList                            │
│  ├─▶ Display channels grid              │
│  ├─▶ Filter by category                 │
│  ├─▶ Search functionality               │
│  └─▶ Favorite toggle                    │
│                                         │
│  SubscriptionPlans                      │
│  ├─▶ Display plans                      │
│  ├─▶ Current subscription status        │
│  └─▶ Select plan action                 │
│                                         │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│         Admin Components                │
├─────────────────────────────────────────┤
│                                         │
│  Admin/Dashboard                        │
│  ├─▶ Statistics cards                   │
│  ├─▶ Revenue charts                     │
│  └─▶ Popular channels list              │
│                                         │
│  Admin/UserManagement                   │
│  ├─▶ Users list with filters            │
│  ├─▶ Ban/Unban actions                  │
│  └─▶ Subscription details               │
│                                         │
│  Admin/ChannelManagement                │
│  ├─▶ Channels CRUD                      │
│  ├─▶ Import from M3U                    │
│  └─▶ Assign to plans                    │
│                                         │
│  Admin/SubscriptionManagement           │
│  ├─▶ Plans CRUD                         │
│  ├─▶ Pricing management                 │
│  └─▶ Features configuration             │
│                                         │
└─────────────────────────────────────────┘
```

### Backend (Laravel)

```
┌─────────────────────────────────────────┐
│            Models                       │
├─────────────────────────────────────────┤
│                                         │
│  User                                   │
│  ├─▶ subscriptions()                    │
│  ├─▶ activeSubscription()               │
│  ├─▶ favorites()                        │
│  ├─▶ watchHistory()                     │
│  └─▶ canAccessChannel()                 │
│                                         │
│  Channel                                │
│  ├─▶ subscriptionPlans()                │
│  ├─▶ watchHistory()                     │
│  ├─▶ favoritedBy()                      │
│  └─▶ incrementViews()                   │
│                                         │
│  SubscriptionPlan                       │
│  ├─▶ channels()                         │
│  ├─▶ subscriptions()                    │
│  └─▶ activeSubscriptions()              │
│                                         │
│  UserSubscription                       │
│  ├─▶ user()                             │
│  ├─▶ plan()                             │
│  ├─▶ isActive()                         │
│  └─▶ daysRemaining()                    │
│                                         │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│          Middleware                     │
├─────────────────────────────────────────┤
│                                         │
│  CheckSubscription                      │
│  ├─▶ Verify user authentication         │
│  ├─▶ Check if user is banned            │
│  ├─▶ Verify active subscription         │
│  └─▶ Check plan access level            │
│                                         │
│  IsAdmin                                │
│  ├─▶ Verify user authentication         │
│  └─▶ Check admin status                 │
│                                         │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│          Commands                       │
├─────────────────────────────────────────┤
│                                         │
│  ImportChannelsFromM3U                  │
│  ├─▶ Download M3U playlist              │
│  ├─▶ Parse channel data                 │
│  ├─▶ Create/Update channels             │
│  └─▶ Assign to subscription plan        │
│                                         │
│  CheckExpiredSubscriptions              │
│  ├─▶ Find expired subscriptions         │
│  └─▶ Update status to 'expired'         │
│                                         │
└─────────────────────────────────────────┘
```

---

## 🔐 Sécurité

### Niveaux de protection

```
┌─────────────────────────────────────────┐
│         Security Layers                 │
├─────────────────────────────────────────┤
│                                         │
│  1. Authentication (Laravel Fortify)    │
│     ├─▶ Email/Password                  │
│     ├─▶ Two-Factor Authentication       │
│     └─▶ Session management              │
│                                         │
│  2. Authorization (Middleware)          │
│     ├─▶ auth: User must be logged in    │
│     ├─▶ admin: User must be admin       │
│     └─▶ subscription: Valid subscription│
│                                         │
│  3. Data Validation                     │
│     ├─▶ Form requests                   │
│     ├─▶ Livewire validation             │
│     └─▶ Database constraints            │
│                                         │
│  4. CSRF Protection                     │
│     └─▶ Automatic token verification    │
│                                         │
│  5. SQL Injection Prevention            │
│     └─▶ Eloquent ORM                    │
│                                         │
└─────────────────────────────────────────┘
```

---

## 📱 Responsive Design

```
┌─────────────────────────────────────────┐
│         Device Support                  │
├─────────────────────────────────────────┤
│                                         │
│  Desktop (1024px+)                      │
│  ├─▶ Full sidebar navigation            │
│  ├─▶ Grid layout (4 columns)            │
│  └─▶ Advanced features visible          │
│                                         │
│  Tablet (768px - 1023px)                │
│  ├─▶ Collapsible sidebar                │
│  ├─▶ Grid layout (2-3 columns)          │
│  └─▶ Touch-optimized controls           │
│                                         │
│  Mobile (< 768px)                       │
│  ├─▶ Bottom navigation                  │
│  ├─▶ Single column layout               │
│  ├─▶ Swipe gestures                     │
│  └─▶ PWA installable                    │
│                                         │
└─────────────────────────────────────────┘
```

---

## 🚀 Performance

### Optimisations implémentées

```
┌─────────────────────────────────────────┐
│         Performance Features            │
├─────────────────────────────────────────┤
│                                         │
│  Database                               │
│  ├─▶ Indexed foreign keys               │
│  ├─▶ Eager loading (with())             │
│  └─▶ Query optimization                 │
│                                         │
│  Frontend                               │
│  ├─▶ Livewire lazy loading              │
│  ├─▶ Asset bundling (Vite)              │
│  ├─▶ Image lazy loading                 │
│  └─▶ Service Worker caching             │
│                                         │
│  Streaming                              │
│  ├─▶ HLS adaptive bitrate               │
│  ├─▶ CDN ready                          │
│  └─▶ Buffer optimization                │
│                                         │
└─────────────────────────────────────────┘
```

---

## 📊 Métriques et monitoring

### Points de mesure

```
┌─────────────────────────────────────────┐
│         Metrics to Track                │
├─────────────────────────────────────────┤
│                                         │
│  Business Metrics                       │
│  ├─▶ Active subscriptions               │
│  ├─▶ Monthly recurring revenue          │
│  ├─▶ Churn rate                         │
│  └─▶ Average revenue per user           │
│                                         │
│  User Engagement                        │
│  ├─▶ Daily active users                 │
│  ├─▶ Watch time per user                │
│  ├─▶ Most watched channels              │
│  └─▶ Favorite channels count            │
│                                         │
│  Technical Metrics                      │
│  ├─▶ Page load time                     │
│  ├─▶ Stream buffering rate              │
│  ├─▶ Error rate                         │
│  └─▶ API response time                  │
│                                         │
└─────────────────────────────────────────┘
```

---

## 🎯 Évolutivité

### Architecture évolutive

```
Current State              Future State
     │                          │
     ├─▶ Monolith              ├─▶ Microservices
     │   (Laravel)             │   ├─▶ Auth Service
     │                         │   ├─▶ Streaming Service
     │                         │   ├─▶ Payment Service
     │                         │   └─▶ Analytics Service
     │                         │
     ├─▶ Single DB             ├─▶ Distributed DB
     │   (MySQL)               │   ├─▶ User DB
     │                         │   ├─▶ Content DB
     │                         │   └─▶ Analytics DB
     │                         │
     ├─▶ Local Storage         ├─▶ Cloud Storage
     │                         │   ├─▶ S3 for videos
     │                         │   └─▶ CDN for delivery
     │                         │
     └─▶ Single Server         └─▶ Load Balanced
                                   ├─▶ App Servers
                                   ├─▶ DB Replicas
                                   └─▶ Cache Layer
```

---

## 📚 Technologies utilisées

```
┌─────────────────────────────────────────┐
│         Tech Stack                      │
├─────────────────────────────────────────┤
│                                         │
│  Backend                                │
│  ├─▶ Laravel 11                         │
│  ├─▶ Livewire 3                         │
│  ├─▶ Flux UI                            │
│  └─▶ MySQL 8                            │
│                                         │
│  Frontend                               │
│  ├─▶ Tailwind CSS                       │
│  ├─▶ Alpine.js                          │
│  ├─▶ HLS.js                             │
│  └─▶ Vite                               │
│                                         │
│  Authentication                         │
│  └─▶ Laravel Fortify                    │
│                                         │
│  PWA                                    │
│  ├─▶ Service Worker                     │
│  ├─▶ Web Manifest                       │
│  └─▶ Offline support                    │
│                                         │
└─────────────────────────────────────────┘
```

---

**Version** : 1.0.0  
**Date** : 17 janvier 2025  
**Statut** : ✅ Production Ready
