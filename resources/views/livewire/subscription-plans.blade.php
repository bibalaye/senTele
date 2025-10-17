<div class="min-h-screen bg-gray-50 dark:bg-dark-950">
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="relative z-10 text-center px-4">
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-6 animate-fade-in">
                Choisissez votre <span class="text-primary-400">abonnement</span>
            </h1>
            <p class="text-xl text-gray-300 mb-8 animate-slide-up">
                Accédez à des milliers de chaînes en direct, films et séries
            </p>
            <div class="flex items-center justify-center gap-4 text-sm text-gray-400">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Sans engagement</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Résiliation facile</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Support 24/7</span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 -mt-20 relative z-20 pb-20">

        @if($currentSubscription)
            <div class="card-premium p-6 mb-8 border-l-4 border-primary-500">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center">
                            <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-lg text-gray-900 dark:text-white">Abonnement actuel: {{ $currentSubscription->plan->name }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Expire le {{ $currentSubscription->expires_at->format('d/m/Y') }} 
                                <span class="text-primary-600 dark:text-primary-400 font-semibold">({{ $currentSubscription->daysRemaining() }} jours restants)</span>
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $currentSubscription->plan->formatted_price }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">par mois</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($plans as $plan)
                <div class="plan-card {{ $plan->slug === 'premium' ? 'featured' : '' }} {{ $currentSubscription && $currentSubscription->plan->id === $plan->id ? 'ring-2 ring-primary-500' : '' }}">
                    
                    @if($plan->slug === 'premium')
                        <div class="absolute top-4 right-4 z-10">
                            <span class="premium-badge">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                POPULAIRE
                            </span>
                        </div>
                    @endif

                    <!-- Header -->
                    <div class="plan-card-header">
                        <h3 class="text-2xl font-bold mb-2">{{ $plan->name }}</h3>
                        <div class="mb-4">
                            <span class="text-5xl font-bold">{{ number_format($plan->price, 0, ',', ' ') }}</span>
                            <span class="text-xl opacity-90">XOF</span>
                        </div>
                        <p class="text-sm opacity-75">/ {{ $plan->duration_days }} jours</p>
                    </div>

                    <!-- Body -->
                    <div class="p-8">
                        <p class="text-gray-600 dark:text-gray-400 mb-6">{{ $plan->description }}</p>
                        
                        <ul class="space-y-3 mb-8">
                            @if($plan->features)
                                @foreach($plan->features as $feature)
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-primary-500 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="text-gray-700 dark:text-gray-300 text-sm">{{ $feature }}</span>
                                    </li>
                                @endforeach
                            @endif
                        </ul>

                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-6 pb-6 border-b border-gray-200 dark:border-dark-700">
                            <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            <span>Jusqu'à {{ $plan->max_devices }} appareil(s)</span>
                        </div>

                        @if($currentSubscription && $currentSubscription->plan->id === $plan->id)
                            <button disabled class="w-full py-3 bg-gray-200 dark:bg-dark-700 text-gray-500 dark:text-gray-400 rounded-lg font-semibold cursor-not-allowed">
                                ✓ Abonnement actuel
                            </button>
                        @elseif($plan->isFree())
                            <button wire:click="selectPlan({{ $plan->id }})" 
                                class="btn-secondary w-full">
                                Commencer gratuitement
                            </button>
                        @else
                            <button wire:click="selectPlan({{ $plan->id }})" 
                                class="btn-primary w-full">
                                Choisir ce plan
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="mt-20 max-w-4xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Questions fréquentes</h2>
            <p class="text-gray-600 dark:text-gray-400">Tout ce que vous devez savoir sur nos abonnements</p>
        </div>
        <div class="space-y-4">
            <div class="card-premium p-6 hover:border-primary-500/50">
                <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Puis-je changer de plan à tout moment ?
                </h3>
                <p class="text-gray-600 dark:text-gray-400">Oui, vous pouvez passer à un plan supérieur ou inférieur à tout moment. Le changement est immédiat.</p>
            </div>
            <div class="card-premium p-6 hover:border-primary-500/50">
                <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Comment annuler mon abonnement ?
                </h3>
                <p class="text-gray-600 dark:text-gray-400">Vous pouvez annuler votre abonnement depuis votre profil à tout moment. Vous garderez l'accès jusqu'à la fin de la période payée.</p>
            </div>
            <div class="card-premium p-6 hover:border-primary-500/50">
                <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    Quels sont les moyens de paiement acceptés ?
                </h3>
                <p class="text-gray-600 dark:text-gray-400">Nous acceptons Wave Money, Orange Money, Free Money et les cartes bancaires via Stripe.</p>
            </div>
        </div>
    </div>
</div>
