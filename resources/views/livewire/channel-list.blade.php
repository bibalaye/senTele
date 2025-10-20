<div class="min-h-screen bg-gradient-to-br from-slate-50 via-slate-100 to-slate-50 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 p-4 sm:p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section with Animation -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-8 animate-fade-in">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg blur-md opacity-50 animate-pulse"></div>
                        <div class="relative bg-gradient-to-br from-green-500 to-emerald-600 p-2 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <h1 class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 dark:from-white dark:via-slate-100 dark:to-white bg-clip-text text-transparent">
                        Chaînes en direct
                    </h1>
                </div>
                <div class="flex items-center gap-3 text-sm">
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm rounded-full border border-slate-200/50 dark:border-slate-700/50 shadow-sm">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                        </span>
                        <span class="font-medium text-slate-700 dark:text-slate-300">{{ $channels->count() }} chaîne{{ $channels->count() > 1 ? 's' : '' }}</span>
                    </div>
                    <div class="px-3 py-1.5 bg-gradient-to-r from-green-500/10 to-emerald-500/10 dark:from-green-500/20 dark:to-emerald-500/20 backdrop-blur-sm rounded-full border border-green-500/20 dark:border-green-500/30">
                        <span class="text-green-700 dark:text-green-400 font-medium">En ligne</span>
                    </div>
                </div>
            </div>

            <!-- Install Button with Animation -->
            <button 
                id="installButton" 
                class="hidden group relative overflow-hidden px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-semibold shadow-lg shadow-green-500/30 hover:shadow-xl hover:shadow-green-500/40 transition-all duration-300 hover:scale-105"
                onclick="installPWA()"
            >
                <div class="absolute inset-0 bg-gradient-to-r from-emerald-600 to-green-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative flex items-center gap-2">
                    <svg class="w-5 h-5 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    <span>Installer l'app</span>
                </div>
            </button>
        </div>

        <!-- Search and Filters with Enhanced Design -->
        <div class="flex flex-col gap-4 sm:flex-row mb-8 animate-slide-up">
            <!-- Search with Glow Effect -->
            <div class="flex-1 relative group">
                <div class="absolute inset-0 bg-gradient-to-r from-green-500/20 to-emerald-500/20 rounded-xl blur-xl opacity-0 group-focus-within:opacity-100 transition-opacity duration-300"></div>
                <div class="relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 group-focus-within:text-green-500 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input 
                        type="text"
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Rechercher une chaîne..." 
                        class="w-full pl-12 pr-4 py-3.5 bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm border border-slate-200/50 dark:border-slate-700/50 rounded-xl text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-green-500/50 focus:border-green-500/50 transition-all duration-300 shadow-sm hover:shadow-md"
                    />
                    @if($search)
                        <button 
                            wire:click="$set('search', '')"
                            class="absolute right-4 top-1/2 -translate-y-1/2 w-6 h-6 flex items-center justify-center rounded-full bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors"
                        >
                            <svg class="w-4 h-4 text-slate-600 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    @endif
                </div>
            </div>

            <!-- Category Filter with Pills -->
            <div class="flex items-center gap-2 overflow-x-auto pb-2 sm:pb-0 scrollbar-hide">
                <button 
                    wire:click="$set('category', '')"
                    class="group relative px-5 py-2.5 rounded-xl text-sm font-semibold whitespace-nowrap transition-all duration-300 {{ $category === '' ? 'bg-gradient-to-r from-green-500 to-emerald-600 text-white shadow-lg shadow-green-500/30 scale-105' : 'bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm text-slate-700 dark:text-slate-300 hover:bg-white dark:hover:bg-slate-800 border border-slate-200/50 dark:border-slate-700/50 hover:scale-105 hover:shadow-md' }}"
                >
                    @if($category === '')
                        <span class="absolute inset-0 bg-gradient-to-r from-emerald-600 to-green-500 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                    @endif
                    <span class="relative flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        Tout
                    </span>
                </button>
                @foreach($categories as $cat)
                    <button 
                        wire:click="$set('category', '{{ $cat }}')"
                        class="group relative px-5 py-2.5 rounded-xl text-sm font-semibold whitespace-nowrap transition-all duration-300 {{ $category === $cat ? 'bg-gradient-to-r from-green-500 to-emerald-600 text-white shadow-lg shadow-green-500/30 scale-105' : 'bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm text-slate-700 dark:text-slate-300 hover:bg-white dark:hover:bg-slate-800 border border-slate-200/50 dark:border-slate-700/50 hover:scale-105 hover:shadow-md' }}"
                    >
                        @if($category === $cat)
                            <span class="absolute inset-0 bg-gradient-to-r from-emerald-600 to-green-500 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                        @endif
                        <span class="relative">{{ $cat }}</span>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Channels Grid -->
        @if($channels->isEmpty())
            <div class="relative overflow-hidden bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm rounded-2xl border border-slate-200/50 dark:border-slate-700/50 shadow-xl p-12 animate-fade-in">
                <div class="absolute inset-0 bg-gradient-to-br from-green-500/5 to-emerald-500/5"></div>
                <div class="relative flex flex-col items-center justify-center text-center">
                    <div class="relative mb-6">
                        <div class="absolute inset-0 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full blur-2xl opacity-20 animate-pulse"></div>
                        <div class="relative bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-700 p-6 rounded-full">
                            <svg class="w-16 h-16 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-2">Aucune chaîne trouvée</h3>
                    <p class="text-slate-600 dark:text-slate-400 mb-6 max-w-md">Essayez une autre recherche ou sélectionnez une catégorie différente</p>
                    @if($search || $category)
                        <button 
                            wire:click="$set('search', ''); $set('category', '')"
                            class="px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-semibold shadow-lg shadow-green-500/30 hover:shadow-xl hover:shadow-green-500/40 transition-all duration-300 hover:scale-105"
                        >
                            Réinitialiser les filtres
                        </button>
                    @endif
                </div>
            </div>
        @else
        @php
            $chunkedChannels = $channels->chunk(4); // 4 chaînes par ligne
        @endphp

        <div class="space-y-10">
            @foreach($chunkedChannels as $chunkIndex => $channelChunk)
                    <div class="animate-slide-up" style="animation-delay: {{ $chunkIndex * 100 }}ms;">
                        <!-- Section Header with Gradient -->
                        <div class="flex items-center justify-between mb-6 group">
                            <div class="flex items-center gap-3">
                                <div class="relative">
                                    @if($chunkIndex === 0)
                                        <div class="absolute inset-0 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-lg blur-md opacity-50 group-hover:opacity-75 transition-opacity"></div>
                                        <div class="relative bg-gradient-to-br from-yellow-500 to-orange-600 p-2 rounded-lg shadow-lg">
                                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        </div>
                                    @elseif($chunkIndex === 1)
                                        <div class="absolute inset-0 bg-gradient-to-br from-red-500 to-pink-600 rounded-lg blur-md opacity-50 group-hover:opacity-75 transition-opacity"></div>
                                        <div class="relative bg-gradient-to-br from-red-500 to-pink-600 p-2 rounded-lg shadow-lg">
                                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="absolute inset-0 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg blur-md opacity-50 group-hover:opacity-75 transition-opacity"></div>
                                        <div class="relative bg-gradient-to-br from-blue-500 to-indigo-600 p-2 rounded-lg shadow-lg">
                                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <h3 class="text-xl font-bold bg-gradient-to-r from-slate-900 to-slate-700 dark:from-white dark:to-slate-300 bg-clip-text text-transparent">
                                    @if($chunkIndex === 0)
                                        Populaires
                                    @elseif($chunkIndex === 1)
                                        Recommandées
                                    @else
                                        Plus de chaînes
                                    @endif
                                </h3>
                            </div>
                            <div class="flex items-center gap-2 px-3 py-1.5 bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm rounded-full border border-slate-200/50 dark:border-slate-700/50">
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $channelChunk->count() }}</span>
                                <span class="text-xs text-slate-500 dark:text-slate-400">chaînes</span>
                            </div>
                        </div>
                    <div class="flex gap-4 overflow-x-auto pb-4 snap-x snap-mandatory scrollbar-hide">
                            @foreach($channelChunk as $channel)
                                <div wire:key="channel-{{ $channel->id }}" class="channel-card group cursor-pointer flex-shrink-0 w-[calc(50%-0.5rem)] sm:w-[calc(33.333%-0.667rem)] lg:w-[calc(25%-0.75rem)] snap-start overflow-hidden rounded-xl bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm border border-slate-200/50 dark:border-slate-700/50 shadow-lg hover:shadow-2xl" wire:click="selectChannel({{ $channel->id }})">
                                    <!-- Channel Image -->
                                    <div class="relative aspect-video bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-700 overflow-hidden">
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

                                        <!-- Play Overlay with Enhanced Animation -->
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center">
                                            <div class="relative">
                                                <div class="absolute inset-0 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full blur-xl opacity-75 animate-pulse"></div>
                                                <div class="relative w-20 h-20 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center shadow-2xl transform group-hover:scale-110 transition-transform duration-300">
                                                    <svg class="w-8 h-8 text-white ml-1" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M8 5v14l11-7z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Live Badge with Glow -->
                                        <div class="absolute top-3 left-3 group/badge">
                                            <div class="absolute inset-0 bg-red-500 rounded-lg blur-md opacity-50 group-hover/badge:opacity-75 transition-opacity"></div>
                                            <div class="relative px-2.5 py-1 bg-gradient-to-r from-red-600 to-red-500 rounded-lg shadow-lg flex items-center gap-1.5">
                                                <span class="relative flex h-2 w-2">
                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
                                                </span>
                                                <span class="text-white text-[10px] font-bold tracking-wider">LIVE</span>
                                            </div>
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

                                    <!-- Channel Info with Enhanced Design -->
                                    <div class="p-4 space-y-3 bg-gradient-to-b from-white/50 to-white dark:from-slate-800/50 dark:to-slate-800">
                                        <h3 class="font-bold text-sm text-slate-900 dark:text-white line-clamp-2 min-h-[2.5rem] group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors">
                                            {{ $channel->name }}
                                        </h3>
                                        
                                        <div class="flex flex-wrap gap-2">
                                            @if($channel->category)
                                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-500/10 dark:bg-green-500/20 rounded-lg border border-green-500/20 dark:border-green-500/30">
                                                    <svg class="w-3 h-3 flex-shrink-0 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                                                    </svg>
                                                    <span class="text-xs font-semibold text-green-700 dark:text-green-300">{{ $channel->category }}</span>
                                                </div>
                                            @endif

                                            @if($channel->country)
                                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-500/10 dark:bg-slate-500/20 rounded-lg border border-slate-500/20 dark:border-slate-500/30">
                                                    <svg class="w-3 h-3 flex-shrink-0 text-slate-600 dark:text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <span class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $channel->country }}</span>
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
                
                @keyframes fade-in {
                    from {
                        opacity: 0;
                        transform: translateY(10px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }
                
                @keyframes slide-up {
                    from {
                        opacity: 0;
                        transform: translateY(20px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }
                
                .animate-fade-in {
                    animation: fade-in 0.5s ease-out;
                }
                
                .animate-slide-up {
                    animation: slide-up 0.6s ease-out;
                }
                
                /* Smooth hover transitions */
                .channel-card {
                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                }
                
                .channel-card:hover {
                    transform: translateY(-8px) scale(1.02);
                }
            </style>
        @endif
    </div>

    <!-- Video Player Modal -->
    @if($selectedChannel)
        <div style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 9999; background: rgba(0,0,0,0.7); display: flex; align-items: center; justify-content: center; padding: 1rem;" wire:click="closePlayer">
            <div style="max-width: 800px; width: 90%; background: #1a1a1a; border-radius: 0.75rem; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.8);" wire:click.stop>
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

                    <!-- Fullscreen Button -->
                    <button 
                        onclick="document.getElementById('main-video-player').requestFullscreen()"
                        style="position: absolute; top: 1rem; right: 4rem; width: 2.5rem; height: 2.5rem; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); border-radius: 9999px; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 20; border: none;"
                        onmouseover="this.style.background='rgba(0,0,0,0.8)'"
                        onmouseout="this.style.background='rgba(0,0,0,0.6)'"
                        title="Plein écran"
                    >
                        <svg style="width: 1.25rem; height: 1.25rem; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                        </svg>
                    </button>

                    <!-- Close Button -->
                    <button 
                        wire:click="closePlayer"
                        style="position: absolute; top: 1rem; right: 1rem; width: 2.5rem; height: 2.5rem; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); border-radius: 9999px; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 20; border: none;"
                        onmouseover="this.style.background='rgba(0,0,0,0.8)'"
                        onmouseout="this.style.background='rgba(0,0,0,0.6)'"
                        title="Fermer"
                    >
                        <svg style="width: 1.25rem; height: 1.25rem; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Channel Info Bar -->
                <div style="border-top: 1px solid #333; padding: 0.75rem 1rem; background: #1a1a1a;">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4 min-w-0 flex-1">
                            <span class="flex-shrink-0 px-2 py-1 bg-red-600 rounded text-white text-xs font-bold flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span>
                                En direct
                            </span>

                            <div class="min-w-0 flex-1">
                                <h3 style="font-weight: 600; color: white; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $selectedChannel->name }}</h3>
                                <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.25rem;">
                                    <span style="font-size: 0.75rem; color: #9ca3af;">{{ $selectedChannel->category }}</span>
                                    <span style="font-size: 0.75rem; color: #6b7280;">•</span>
                                    <span style="font-size: 0.75rem; color: #9ca3af;">{{ $selectedChannel->country }}</span>
                                </div>
                            </div>
                        </div>

                        @auth
                            <button 
                                wire:click="toggleFavorite({{ $selectedChannel->id }})"
                                style="flex-shrink: 0; width: 2.25rem; height: 2.25rem; background: #333; border-radius: 9999px; display: flex; align-items: center; justify-content: center; cursor: pointer; border: none; transition: background 0.2s;"
                                onmouseover="this.style.background='#444'"
                                onmouseout="this.style.background='#333'"
                            >
                                @if(auth()->user()->favorites()->where('channel_id', $selectedChannel->id)->exists())
                                    <svg style="width: 1.25rem; height: 1.25rem; color: #ef4444; fill: currentColor;" viewBox="0 0 24 24">
                                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                    </svg>
                                @else
                                    <svg style="width: 1.25rem; height: 1.25rem; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
    @assets
    <script>
        // Player HLS - Init immédiat
        const streamUrl = @js(route('stream.proxy', ['channel' => $selectedChannel->id]));
        const channelName = @js($selectedChannel->name);
        
        console.log('🎬 Loading:', channelName);
        console.log('📺 URL:', streamUrl);
        
        // Attendre que le DOM soit prêt
        const initPlayer = () => {
            const video = document.getElementById('main-video-player');
            const loading = document.getElementById('video-loading');
            
            if (!video) {
                console.error('❌ Video element not found');
                setTimeout(initPlayer, 100);
                return;
            }
            
            console.log('✅ Video element found');
            
            // Cleanup
            if (window.currentHls) {
                console.log('🧹 Cleaning previous HLS');
                window.currentHls.destroy();
                window.currentHls = null;
            }
            
            if (typeof Hls === 'undefined') {
                console.error('❌ HLS.js not loaded');
                return;
            }
            
            console.log('✅ HLS.js version:', Hls.version);
            
            // HLS.js
            if (Hls.isSupported()) {
                console.log('✅ HLS.js supported');
                const hls = new Hls({
                    debug: false,
                    enableWorker: true,
                    lowLatencyMode: true
                });
                
                hls.on(Hls.Events.MANIFEST_PARSED, () => {
                    console.log('✅ Stream ready');
                    if (loading) loading.style.display = 'none';
                    video.play()
                        .then(() => console.log('▶️ Playing'))
                        .catch(e => console.log('⚠️ Autoplay:', e.message));
                });
                
                hls.on(Hls.Events.ERROR, (event, data) => {
                    console.error('❌ Error:', data.type, data.details);
                    if (data.fatal) {
                        if (data.type === Hls.ErrorTypes.NETWORK_ERROR) {
                            console.log('🔄 Retry network');
                            hls.startLoad();
                        } else if (data.type === Hls.ErrorTypes.MEDIA_ERROR) {
                            console.log('🔄 Recover media');
                            hls.recoverMediaError();
                        }
                    }
                });
                
                console.log('📡 Loading...');
                hls.loadSource(streamUrl);
                hls.attachMedia(video);
                window.currentHls = hls;
                
            } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                console.log('✅ Native HLS');
                video.src = streamUrl;
                video.addEventListener('loadeddata', () => {
                    if (loading) loading.style.display = 'none';
                });
                video.play().catch(e => console.log('⚠️ Autoplay:', e.message));
            } else {
                console.error('❌ HLS not supported');
            }
        };
        
        // Init après un court délai
        setTimeout(initPlayer, 300);
    </script>
    @endassets
    @endif
</div>
<!-- Fin du composant Livewire -->
