/**
 * Sentele V2 - Service Worker
 * Gestion du cache et mode offline
 */

const CACHE_VERSION = 'sentele-v2-1.0.0';
const CACHE_STATIC = `${CACHE_VERSION}-static`;
const CACHE_DYNAMIC = `${CACHE_VERSION}-dynamic`;
const CACHE_IMAGES = `${CACHE_VERSION}-images`;

// Fichiers à mettre en cache immédiatement
const STATIC_ASSETS = [
    '/',
    '/manifest.json',
    '/offline.html',
];

// Installer le Service Worker
self.addEventListener('install', (event) => {
    console.log('[SW] Installation...');
    
    event.waitUntil(
        caches.open(CACHE_STATIC)
            .then(cache => {
                console.log('[SW] Cache statique créé');
                return cache.addAll(STATIC_ASSETS);
            })
            .catch(err => console.error('[SW] Erreur cache statique:', err))
    );
    
    // Forcer l'activation immédiate
    self.skipWaiting();
});

// Activer le Service Worker
self.addEventListener('activate', (event) => {
    console.log('[SW] Activation...');
    
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames
                    .filter(name => name.startsWith('sentele-v2-') && name !== CACHE_STATIC && name !== CACHE_DYNAMIC && name !== CACHE_IMAGES)
                    .map(name => {
                        console.log('[SW] Suppression ancien cache:', name);
                        return caches.delete(name);
                    })
            );
        })
    );
    
    // Prendre le contrôle immédiatement
    return self.clients.claim();
});

// Intercepter les requêtes
self.addEventListener('fetch', (event) => {
    const { request } = event;
    
    // Ignorer les extensions de navigateur et protocoles non-HTTP
    if (request.url.startsWith('chrome-extension://') || 
        request.url.startsWith('moz-extension://') ||
        request.url.startsWith('safari-extension://') ||
        !request.url.startsWith('http')) {
        return;
    }
    
    const url = new URL(request.url);

    // Ne pas mettre en cache les flux vidéo (trop volumineux)
    if (request.url.includes('.m3u8') || request.url.includes('.ts') || request.url.includes('stream')) {
        return;
    }

    // Stratégie: Network First pour les pages HTML
    if (request.headers.get('accept').includes('text/html')) {
        event.respondWith(
            fetch(request)
                .then(response => {
                    // Cloner et mettre en cache
                    const responseClone = response.clone();
                    caches.open(CACHE_DYNAMIC).then(cache => {
                        cache.put(request, responseClone);
                    });
                    return response;
                })
                .catch(() => {
                    // Fallback sur le cache
                    return caches.match(request)
                        .then(cached => cached || caches.match('/offline.html'));
                })
        );
        return;
    }

    // Stratégie: Cache First pour les images
    if (request.url.match(/\.(jpg|jpeg|png|gif|svg|webp|ico)$/)) {
        event.respondWith(
            caches.match(request)
                .then(cached => {
                    if (cached) {
                        return cached;
                    }
                    
                    return fetch(request).then(response => {
                        // Cloner et mettre en cache
                        const responseClone = response.clone();
                        caches.open(CACHE_IMAGES).then(cache => {
                            cache.put(request, responseClone);
                        });
                        return response;
                    });
                })
        );
        return;
    }

    // Stratégie: Network First pour les assets CSS/JS
    if (request.url.match(/\.(css|js)$/)) {
        event.respondWith(
            fetch(request)
                .then(response => {
                    const responseClone = response.clone();
                    caches.open(CACHE_STATIC).then(cache => {
                        cache.put(request, responseClone);
                    });
                    return response;
                })
                .catch(() => caches.match(request))
        );
        return;
    }

    // Par défaut: Network First
    event.respondWith(
        fetch(request)
            .catch(() => caches.match(request))
    );
});

// Gérer les messages
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
});
