/**
 * Sentele V2 - HLS Player Manager
 * Gestion du lecteur vidéo avec HLS.js pour tous les appareils
 */

class SentelePlayer {
    constructor() {
        this.hls = null;
        this.videoElement = null;
        this.currentChannel = null;
    }

    /**
     * Initialiser le lecteur HLS
     */
    init(videoElementId, streamUrl, channelData) {
        this.videoElement = document.getElementById(videoElementId);
        this.currentChannel = channelData;

        if (!this.videoElement) {
            console.error('Video element not found');
            return;
        }

        // Détruire l'instance précédente si elle existe
        this.destroy();

        // Vérifier que HLS.js est chargé
        if (typeof Hls === 'undefined') {
            console.error('HLS.js not loaded yet, retrying...');
            setTimeout(() => this.init(videoElementId, streamUrl, channelData), 100);
            return;
        }

        // Vérifier le support HLS
        if (Hls.isSupported()) {
            this.initHls(streamUrl);
        } else if (this.videoElement.canPlayType('application/vnd.apple.mpegurl')) {
            // Support natif (Safari, iOS)
            this.initNative(streamUrl);
        } else {
            this.handleError('HLS non supporté sur cet appareil');
        }
    }

    /**
     * Initialiser HLS.js (Chrome, Firefox, Edge, etc.)
     */
    initHls(streamUrl) {
        this.hls = new Hls({
            enableWorker: true,
            lowLatencyMode: true,
            backBufferLength: 90,
            maxBufferLength: 30,
            maxMaxBufferLength: 600,
            maxBufferSize: 60 * 1000 * 1000,
            maxBufferHole: 0.5,
            highBufferWatchdogPeriod: 2,
            nudgeOffset: 0.1,
            nudgeMaxRetry: 3,
            maxFragLookUpTolerance: 0.25,
            liveSyncDurationCount: 3,
            liveMaxLatencyDurationCount: 10,
            liveDurationInfinity: true,
            enableWebVTT: false,
            enableCEA708Captions: false,
            stretchShortVideoTrack: false,
            maxAudioFramesDrift: 1,
            forceKeyFrameOnDiscontinuity: true,
            abrEwmaFastLive: 3.0,
            abrEwmaSlowLive: 9.0,
            abrEwmaFastVoD: 3.0,
            abrEwmaSlowVoD: 9.0,
            abrEwmaDefaultEstimate: 500000,
            abrBandWidthFactor: 0.95,
            abrBandWidthUpFactor: 0.7,
            abrMaxWithRealBitrate: false,
            maxStarvationDelay: 4,
            maxLoadingDelay: 4,
            minAutoBitrate: 0,
            emeEnabled: false,
            widevineLicenseUrl: undefined,
            requestMediaKeySystemAccessFunc: null,
        });

        this.hls.loadSource(streamUrl);
        this.hls.attachMedia(this.videoElement);

        // Events HLS
        this.hls.on(Hls.Events.MANIFEST_PARSED, () => {
            console.log('✅ Manifest chargé');
            this.hideLoading();
            this.play();
        });

        this.hls.on(Hls.Events.ERROR, (event, data) => {
            this.handleHlsError(data);
        });

        this.hls.on(Hls.Events.FRAG_LOADED, () => {
            this.hideLoading();
        });
    }

    /**
     * Initialiser lecteur natif (Safari/iOS)
     */
    initNative(streamUrl) {
        this.videoElement.src = streamUrl;
        
        this.videoElement.addEventListener('loadeddata', () => {
            console.log('✅ Vidéo chargée (natif)');
            this.hideLoading();
        });

        this.videoElement.addEventListener('error', (e) => {
            this.handleError('Erreur de lecture native');
        });

        this.play();
    }

    /**
     * Lancer la lecture
     */
    play() {
        if (this.videoElement) {
            this.videoElement.play()
                .then(() => console.log('▶️ Lecture démarrée'))
                .catch(e => {
                    console.warn('Autoplay bloqué:', e);
                    // L'utilisateur devra cliquer sur play manuellement
                });
        }
    }

    /**
     * Gérer les erreurs HLS
     */
    handleHlsError(data) {
        console.error('HLS Error:', data);

        if (data.fatal) {
            switch (data.type) {
                case Hls.ErrorTypes.NETWORK_ERROR:
                    console.error('Erreur réseau, tentative de récupération...');
                    this.hls.startLoad();
                    break;
                
                case Hls.ErrorTypes.MEDIA_ERROR:
                    console.error('Erreur média, tentative de récupération...');
                    this.hls.recoverMediaError();
                    break;
                
                default:
                    this.handleError('Erreur fatale de lecture');
                    this.destroy();
                    break;
            }
        }
    }

    /**
     * Afficher une erreur
     */
    handleError(message) {
        console.error(message);
        this.hideLoading();
        
        const spinner = document.getElementById('video-loading');
        if (spinner) {
            spinner.innerHTML = `
                <div class="text-center px-4">
                    <svg class="w-16 h-16 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-white text-lg font-semibold mb-2">Erreur de lecture</p>
                    <p class="text-gray-400 text-sm mb-4">${message}</p>
                    <button onclick="window.livewire.find('${this.getComponentId()}').call('retryStream')" 
                            class="px-4 py-2 bg-white text-black rounded-lg hover:bg-gray-200 transition-colors font-medium">
                        Réessayer
                    </button>
                </div>
            `;
        }

        // Dispatch event vers Livewire
        if (window.Livewire) {
            window.Livewire.dispatch('player-error');
        }
    }

    /**
     * Masquer le spinner de chargement
     */
    hideLoading() {
        const spinner = document.getElementById('video-loading');
        if (spinner) {
            spinner.style.display = 'none';
        }
    }

    /**
     * Détruire le lecteur et libérer les ressources
     */
    destroy() {
        if (this.hls) {
            this.hls.destroy();
            this.hls = null;
        }

        if (this.videoElement) {
            this.videoElement.pause();
            this.videoElement.src = '';
            this.videoElement.load();
        }

        this.currentChannel = null;
    }

    /**
     * Obtenir l'ID du composant Livewire
     */
    getComponentId() {
        const component = document.querySelector('[wire\\:id]');
        return component ? component.getAttribute('wire:id') : null;
    }
}

// Instance globale
window.sentelePlayer = new SentelePlayer();

// Écouter les événements Livewire
document.addEventListener('livewire:init', () => {
    console.log('✅ Livewire initialized, player ready');
    
    // Quand une chaîne est sélectionnée
    Livewire.on('channel-selected', (event) => {
        setTimeout(() => {
            const videoElement = document.getElementById('main-video-player');
            const selectedChannel = event.channelId;
            
            if (videoElement) {
                // Récupérer l'URL du stream depuis l'attribut data
                const streamUrl = videoElement.getAttribute('data-stream-url');
                
                if (streamUrl) {
                    window.sentelePlayer.init('main-video-player', streamUrl, {
                        id: selectedChannel
                    });
                }
            }
        }, 100);
    });

    // Quand le lecteur se ferme
    Livewire.on('player-closing', () => {
        window.sentelePlayer.destroy();
    });
});

// Nettoyer à la fermeture de la page
window.addEventListener('beforeunload', () => {
    window.sentelePlayer.destroy();
});
