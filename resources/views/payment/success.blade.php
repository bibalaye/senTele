<x-layouts.app.sidebar>
    <flux:main class="min-h-screen bg-gray-50 dark:bg-slate-950 p-6">
        <div class="max-w-2xl mx-auto text-center">
            <!-- Icône de succès -->
            <div class="mb-6">
                <div class="w-24 h-24 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto animate-bounce">
                    <svg class="w-12 h-12 text-green-500 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>

            <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white">Abonnement activé avec succès !</h1>
            <p class="text-gray-600 dark:text-gray-400 mb-8">
                Votre abonnement <span class="font-semibold text-green-600 dark:text-green-400">{{ $subscription->plan->name }}</span> est maintenant actif.
            </p>

            <!-- Détails de l'abonnement -->
            <div class="card-premium p-6 mb-8">
                <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Détails de votre abonnement</h2>
                
                <div class="space-y-3 text-left">
                    <div class="flex justify-between py-2 border-b border-gray-200 dark:border-slate-700">
                        <span class="text-gray-600 dark:text-gray-400">Plan</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $subscription->plan->name }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-200 dark:border-slate-700">
                        <span class="text-gray-600 dark:text-gray-400">Prix</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $subscription->plan->formatted_price }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-200 dark:border-slate-700">
                        <span class="text-gray-600 dark:text-gray-400">Date de début</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $subscription->starts_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-200 dark:border-slate-700">
                        <span class="text-gray-600 dark:text-gray-400">Date d'expiration</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $subscription->expires_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-200 dark:border-slate-700">
                        <span class="text-gray-600 dark:text-gray-400">Durée</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $subscription->plan->duration_days }} jours</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-gray-600 dark:text-gray-400">Appareils simultanés</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $subscription->plan->max_devices }}</span>
                    </div>
                </div>
            </div>

            <!-- Fonctionnalités incluses -->
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg p-6 mb-8">
                <h3 class="font-bold mb-4 text-gray-900 dark:text-white">Fonctionnalités incluses</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-left">
                    @if($subscription->plan->features)
                        @foreach($subscription->plan->features as $feature)
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 dark:text-green-400 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $feature }}</span>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('channels') }}" class="btn-primary">
                    Découvrir les chaînes
                </a>
                <a href="{{ route('dashboard') }}" class="btn-secondary">
                    Retour au dashboard
                </a>
            </div>

            <!-- Informations supplémentaires -->
            <div class="mt-8 text-sm text-gray-600 dark:text-gray-400">
                <p>Un email de confirmation a été envoyé à <span class="font-semibold text-gray-900 dark:text-white">{{ auth()->user()->email }}</span></p>
                <p class="mt-2">Pour toute question, contactez notre support.</p>
            </div>
        </div>
    </flux:main>
</x-layouts.app.sidebar>
