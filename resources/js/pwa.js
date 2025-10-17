/**
 * Sentele V2 - PWA Manager
 * Gestion de l'installation PWA et du mode offline
 */

let deferredPrompt;
let installButton;

/**
 * Initialiser la PWA
 */
function initPWA() {
    installButton = document.getElementById('installButton');

    // Écouter l'événement beforeinstallprompt
    window.addEventListener('beforeinstallprompt', (e) => {
        console.log('💾 PWA installable détectée');
        
        // Empêcher l'affichage automatique
        e.preventDefault();
        deferredPrompt = e;
        
        // Afficher le bouton d'installation
        if (installButton) {
            installButton.classList.remove('hidden');
        }
    });

    // Écouter l'installation réussie
    window.addEventListener('appinstalled', () => {
        console.log('✅ PWA installée avec succès');
        deferredPrompt = null;
        
        if (installButton) {
            installButton.classList.add('hidden');
        }

        // Notification de succès
        showNotification('Application installée !', 'Sentele V2 est maintenant disponible sur votre appareil');
    });

    // Vérifier si déjà installée
    if (window.matchMedia('(display-mode: standalone)').matches) {
        console.log('📱 PWA déjà installée');
        if (installButton) {
            installButton.classList.add('hidden');
        }
    }
}

/**
 * Installer la PWA
 */
window.installPWA = async function() {
    if (!deferredPrompt) {
        console.log('❌ Pas de prompt d\'installation disponible');
        return;
    }

    // Afficher le prompt d'installation
    deferredPrompt.prompt();

    // Attendre la réponse de l'utilisateur
    const { outcome } = await deferredPrompt.userChoice;
    
    console.log(`👤 Choix utilisateur: ${outcome}`);

    if (outcome === 'accepted') {
        console.log('✅ Installation acceptée');
    } else {
        console.log('❌ Installation refusée');
    }

    // Réinitialiser le prompt
    deferredPrompt = null;
    
    if (installButton) {
        installButton.classList.add('hidden');
    }
};

/**
 * Afficher une notification
 */
function showNotification(title, message) {
    // Vérifier le support des notifications
    if (!('Notification' in window)) {
        return;
    }

    // Demander la permission si nécessaire
    if (Notification.permission === 'granted') {
        new Notification(title, {
            body: message,
            icon: '/images/logo-192.png',
            badge: '/images/logo-96.png',
        });
    } else if (Notification.permission !== 'denied') {
        Notification.requestPermission().then(permission => {
            if (permission === 'granted') {
                new Notification(title, {
                    body: message,
                    icon: '/images/logo-192.png',
                    badge: '/images/logo-96.png',
                });
            }
        });
    }
}

/**
 * Enregistrer le Service Worker
 */
function registerServiceWorker() {
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js')
            .then(registration => {
                console.log('✅ Service Worker enregistré:', registration.scope);

                // Vérifier les mises à jour
                registration.addEventListener('updatefound', () => {
                    const newWorker = registration.installing;
                    
                    newWorker.addEventListener('statechange', () => {
                        if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                            console.log('🔄 Nouvelle version disponible');
                            
                            // Afficher un message pour recharger
                            showUpdateNotification();
                        }
                    });
                });
            })
            .catch(error => {
                console.error('❌ Erreur Service Worker:', error);
            });
    }
}

/**
 * Afficher notification de mise à jour
 */
function showUpdateNotification() {
    const notification = document.createElement('div');
    notification.className = 'fixed bottom-4 right-4 bg-zinc-900 text-white px-6 py-4 rounded-lg shadow-2xl z-50 max-w-sm';
    notification.innerHTML = `
        <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            <div class="flex-1">
                <p class="font-semibold mb-1">Mise à jour disponible</p>
                <p class="text-sm text-gray-300 mb-3">Une nouvelle version de Sentele V2 est prête</p>
                <button onclick="window.location.reload()" class="px-4 py-2 bg-white text-black rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                    Mettre à jour
                </button>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="text-gray-400 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
}

/**
 * Détecter le mode d'affichage
 */
function detectDisplayMode() {
    const isStandalone = window.matchMedia('(display-mode: standalone)').matches;
    
    if (isStandalone) {
        console.log('📱 Mode: Application standalone');
        document.body.classList.add('pwa-standalone');
    } else {
        console.log('🌐 Mode: Navigateur');
        document.body.classList.add('pwa-browser');
    }
}

/**
 * Gérer la connectivité
 */
function handleConnectivity() {
    window.addEventListener('online', () => {
        console.log('✅ Connexion rétablie');
        showNotification('Connexion rétablie', 'Vous êtes de nouveau en ligne');
    });

    window.addEventListener('offline', () => {
        console.log('❌ Connexion perdue');
        showNotification('Hors ligne', 'Certaines fonctionnalités peuvent être limitées');
    });
}

// Initialiser au chargement
document.addEventListener('DOMContentLoaded', () => {
    initPWA();
    registerServiceWorker();
    detectDisplayMode();
    handleConnectivity();
});

// Export pour utilisation globale
window.pwaManager = {
    install: window.installPWA,
    showNotification,
};
