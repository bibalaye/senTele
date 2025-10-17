<div class="min-h-screen bg-gray-50 dark:bg-slate-950 p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Chaînes en direct</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2">
                    <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                    {{ $channels->count() }} chaîne{{ $channels->count() > 1 ? 's' : '' }} disponible{{ $channels->count() > 1 ? 's' : '' }}
                </p>
            </div>

            <!-- Install Button -->
            <button 
                id="installButton" 
                class="hidden btn-primary"
                onclick="installPWA()"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Installer l'app
            </button>
        </div>

        <!-- Search and Filters -->
        <div class="flex flex-col gap-4 sm:flex-row mb-8">
            <!-- Search -->
            <div class="flex-1 relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input 
                    type="text"
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Rechercher une chaîne..." 
                    class="input-modern pl-12"
                />
            </div>

            <!-- Category Filter -->
            <div class="flex items-center gap-2 overflow-x-auto pb-2 sm:pb-0 scrollbar-hide">
                <button 
                    wire:click="$set('category', '')"
                    class="px-4 py-2 rounded-lg text-sm font-semibold whitespace-nowrap transition-all {{ $category === '' ? 'bg-gradient-to-r from-slate-900 to-green-600 text-white shadow-lg' : 'bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 border border-gray-200 dark:border-slate-700' }}"
                >
                    Tout
                </button>
                @foreach($categories as $cat)
                    <button 
                        wire:click="$set('category', '{{ $cat }}')"
                        class="px-4 py-2 rounded-lg text-sm font-semibold whitespace-nowrap transition-all {{ $category === $cat ? 'bg-gradient-to-r from-slate-900 to-green-600 text-white shadow-lg' : 'bg-white dark:bg-slate-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 border border-gray-200 dark:border-slate-700' }}"
                    >
                        {{ $cat }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Channels Horizontal Scroll -->
        @if($channels->isEmpty())
            <div class="card-premium flex flex-col items-center justify-center py-20">
                <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Aucune chaîne trouvée</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Essayez une autre recherche ou catégorie</p>
            </div>
        @else
        @php
            $chunkedChannels = $channels->chunk(4); // 4 chaînes par ligne
        @endphp

        <div class="space-y-8">
            @foreach($chunkedChannels as $chunkIndex => $channelChunk)
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                @if($chunkIndex === 0)
                                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    Populaires
                                @elseif($chunkIndex === 1)
                                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path>
                                    </svg>
                                    Recommandées
                                @else
                                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path>
                                    </svg>
                                    Plus de chaînes
                                @endif
                            </h3>
                            <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">{{ $channelChunk->count() }} chaînes</span>
                        </div>
                    <div class="flex gap-4 overflow-x-auto pb-4 snap-x snap-mandatory scrollbar-hide">
                            @foreach($channelChunk as $channel)
                                <div class="channel-card group cursor-pointer flex-shrink-0 w-[calc(50%-0.5rem)] sm:w-[calc(33.333%-0.667rem)] lg:w-[calc(25%-0.75rem)] snap-start overflow-hidden rounded-lg shadow-lg hover:shadow-xl transition-all" wire:click="selectChannel({{ $channel->id }})">
                                    <!-- Channel Image -->
                                    <div class="relative aspect-video bg-gray-100 dark:bg-slate-800 overflow-hidden">
                                        <div class="absolute inset-0 flex items-center justify-center p-4">
                                            @if($channel->logo)
                                                <img 
                                                    src="{{ $channel->logo }}" 
                                                    alt="{{ $channel->name }}" 
                                                    class="max-w-full max-h-full object-contain transition-transform duration-300 group-hover:scale-110"
                                                    loading="lazy"
                                                >
                                            @else
                                                <svg class="w-12 h-12 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                </svg>
                                            @endif
                                        </div>

                                        <!-- Play Overlay -->
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                            <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-full flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform">
                                                <svg class="w-7 h-7 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M8 5v14l11-7z"/>
                                                </svg>
                                            </div>
                                        </div>

                                        <!-- Live Badge -->
                                        <div class="absolute top-2 left-2 px-2 py-0.5 bg-red-600 rounded text-white text-[10px] font-bold flex items-center gap-1">
                                            <span class="w-1 h-1 bg-white rounded-full animate-pulse"></span>
                                            LIVE
                                        </div>

                                        <!-- Favorite Button -->
                                        @auth
                                            <button 
                                                wire:click.stop="toggleFavorite({{ $channel->id }})"
                                                class="absolute top-2 right-2 w-7 h-7 bg-black/60 backdrop-blur-sm rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity hover:bg-black/80"
                                            >
                                                @if(auth()->user()->favorites()->where('channel_id', $channel->id)->exists())
                                                    <svg class="w-3.5 h-3.5 text-red-500 fill-current" viewBox="0 0 24 24">
                                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                                    </svg>
                                                @endif
                                            </button>
                                        @endauth
                                    </div>

                                    <!-- Channel Info -->
                                    <div class="p-4 space-y-2 bg-white dark:bg-slate-800">
                                        <h3 class="font-bold text-sm text-gray-900 dark:text-white line-clamp-2 min-h-[2.5rem]">{{ $channel->name }}</h3>
                                        
                                        <div class="flex flex-col gap-1.5">
                                            @if($channel->category)
                                                <div class="flex items-center gap-1.5 text-xs text-gray-600 dark:text-gray-400">
                                                    <svg class="w-3.5 h-3.5 flex-shrink-0 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                                                    </svg>
                                                    <span class="font-medium">{{ $channel->category }}</span>
                                                </div>
                                            @endif

                                            @if($channel->country)
                                                <div class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-500">
                                                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <span>{{ $channel->country }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                    </div>
                </div>
            @endforeach
            </div>

            <style>
                .scrollbar-hide::-webkit-scrollbar {
                    display: none;
                }
                .scrollbar-hide {
                    -ms-overflow-style: none;
                    scrollbar-width: none;
                }
            </style>
        @endif
    </div>
</div>

        <!-- Video Player Modal -->
        @if($selectedChannel)
            <div class="fixed inset-0 z-50 bg-black/95 backdrop-blur-sm flex items-center justify-center p-4 animate-fade-in" wire:click="closePlayer">
                <div class="card-premium max-w-6xl w-full max-h-[90vh] overflow-hidden shadow-2xl animate-slide-up" wire:click.stop>
                <!-- Video Player -->
                <div class="relative aspect-video bg-black">
                    <!-- Loading State -->
                    <div id="video-loading" class="absolute inset-0 flex items-center justify-center bg-black z-10">
                        <div class="flex flex-col items-center gap-4">
                            <div class="w-12 h-12 border-4 border-white/20 border-t-white rounded-full animate-spin"></div>
                            <p class="text-white/60 text-sm">Chargement...</p>
                        </div>
                    </div>

                    <video 
                        id="main-video-player"
                        class="w-full h-full"
                        controls
                        autoplay
                        controlsList="nodownload"
                        data-stream-url="{{ $selectedChannel->stream_url }}"
                        wire:ignore
                    ></video>

                    <!-- Close Button -->
                    <button 
                        wire:click="closePlayer"
                        class="absolute top-4 right-4 w-10 h-10 bg-black/60 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-black/80 transition-colors z-20"
                    >
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Channel Info Bar -->
                <div class="border-t border-zinc-200 dark:border-zinc-700 p-4 bg-white dark:bg-zinc-900">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4 min-w-0 flex-1">
                            <span class="flex-shrink-0 px-2 py-1 bg-red-600 rounded text-white text-xs font-bold flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span>
                                En direct
                            </span>

                            <div class="min-w-0 flex-1">
                                <h3 class="font-semibold text-zinc-900 dark:text-white truncate">{{ $selectedChannel->name }}</h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs text-zinc-600 dark:text-zinc-400">{{ $selectedChannel->category }}</span>
                                    <span class="text-xs text-zinc-400">•</span>
                                    <span class="text-xs text-zinc-600 dark:text-zinc-400">{{ $selectedChannel->country }}</span>
                                </div>
                            </div>
                        </div>

                        @auth
                            <button 
                                wire:click="toggleFavorite({{ $selectedChannel->id }})"
                                class="flex-shrink-0 w-9 h-9 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 rounded-full flex items-center justify-center transition-colors"
                            >
                                @if(auth()->user()->favorites()->where('channel_id', $selectedChannel->id)->exists())
                                    <svg class="w-5 h-5 text-red-500 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-zinc-600 dark:text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                @endif
                            </button>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

    @endif

    @if($selectedChannel)
    @push('scripts')
    <script>
        // Initialiser le lecteur immédiatement
        (function() {
            const streamUrl = @js(route('stream.proxy', ['channel' => $selectedChannel->id]));
            const channelId = {{ $selectedChannel->id }};
            
            console.log('🎬 Initializing HLS player for channel:', channelId);
            console.log('📺 Stream URL:', streamUrl);
            
            function initPlayer() {
                const videoElement = document.getElementById('main-video-player');
                const loadingElement = document.getElementById('video-loading');
                
                if (!videoElement) {
                    console.error('❌ Video element not found');
                    return;
                }
                
                // Cleanup previous instance
                if (window.currentHls) {
                    console.log('🧹 Cleaning up previous HLS instance');
                    window.currentHls.destroy();
                    window.currentHls = null;
                }
                
                // Check if HLS.js is loaded
                if (typeof Hls === 'undefined') {
                    console.error('❌ HLS.js not loaded');
                    if (loadingElement) {
                        loadingElement.innerHTML = '<div class="text-center"><p class="text-white text-lg mb-2">Erreur</p><p class="text-white/60 text-sm">Bibliothèque HLS non chargée</p></div>';
                    }
                    return;
                }
                
                console.log('✅ HLS.js version:', Hls.version);
                
                // HLS.js support
                if (Hls.isSupported()) {
                    console.log('✅ HLS.js is supported');
                    
                    const hls = new Hls({
                        debug: false,
                        enableWorker: true,
                        lowLatencyMode: true,
                        backBufferLength: 90,
                        maxBufferLength: 30,
                        maxMaxBufferLength: 600
                    });
                    
                    hls.on(Hls.Events.MANIFEST_PARSED, function() {
                        console.log('✅ Manifest parsed successfully');
                        if (loadingElement) loadingElement.style.display = 'none';
                        
                        videoElement.play()
                            .then(() => console.log('▶️ Playing'))
                            .catch(err => console.log('⚠️ Autoplay prevented:', err.message));
                    });
                    
                    hls.on(Hls.Events.ERROR, function(event, data) {
                        console.error('❌ HLS Error:', data.type, data.details);
                        
                        if (data.fatal) {
                            switch(data.type) {
                                case Hls.ErrorTypes.NETWORK_ERROR:
                                    console.log('🔄 Network error, trying to recover...');
                                    hls.startLoad();
                                    break;
                                case Hls.ErrorTypes.MEDIA_ERROR:
                                    console.log('🔄 Media error, trying to recover...');
                                    hls.recoverMediaError();
                                    break;
                                default:
                                    if (loadingElement) {
                                        loadingElement.innerHTML = '<div class="text-center px-4"><p class="text-white text-lg mb-2">❌ Erreur de lecture</p><p class="text-white/60 text-sm">' + data.details + '</p><button onclick="location.reload()" class="mt-4 px-4 py-2 bg-white text-black rounded-lg hover:bg-gray-200">Réessayer</button></div>';
                                    }
                                    hls.destroy();
                                    break;
                            }
                        }
                    });
                    
                    hls.loadSource(streamUrl);
                    hls.attachMedia(videoElement);
                    window.currentHls = hls;
                    
                } else if (videoElement.canPlayType('application/vnd.apple.mpegurl')) {
                    // Native HLS support (Safari, iOS)
                    console.log('✅ Using native HLS support');
                    videoElement.src = streamUrl;
                    
                    videoElement.addEventListener('loadeddata', function() {
                        console.log('✅ Video loaded (native)');
                        if (loadingElement) loadingElement.style.display = 'none';
                    });
                    
                    videoElement.addEventListener('error', function(e) {
                        console.error('❌ Native player error:', e);
                        if (loadingElement) {
                            loadingElement.innerHTML = '<div class="text-center px-4"><p class="text-white text-lg mb-2">❌ Erreur de lecture</p><button onclick="location.reload()" class="mt-4 px-4 py-2 bg-white text-black rounded-lg hover:bg-gray-200">Réessayer</button></div>';
                        }
                    });
                    
                    videoElement.play()
                        .then(() => console.log('▶️ Playing (native)'))
                        .catch(err => console.log('⚠️ Autoplay prevented:', err.message));
                } else {
                    console.error('❌ HLS not supported on this device');
                    if (loadingElement) {
                        loadingElement.innerHTML = '<div class="text-center px-4"><p class="text-white text-lg mb-2">❌ Format non supporté</p><p class="text-white/60 text-sm">Votre navigateur ne supporte pas la lecture HLS</p></div>';
                    }
                }
            }
            
            // Init immediately or wait for DOM
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initPlayer);
            } else {
                setTimeout(initPlayer, 100);
            }
            
            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    if (window.currentHls) {
                        window.currentHls.destroy();
                        window.currentHls = null;
                    }
                    @this.call('closePlayer');
                }
            });
        })();
    </script>
    @endpush
    @endif
</div>
