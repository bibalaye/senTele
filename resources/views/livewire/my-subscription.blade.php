<div class="min-h-screen bg-gray-50 dark:bg-slate-950 p-6">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">Mon abonnement</h1>

        @if (session()->has('message'))
            <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg mb-6">
                {{ session('message') }}
            </div>
        @endif

        @if($subscription)
            <!-- Abonnement actif -->
            <div class="card-premium overflow-hidden mb-6">
                <div class="plan-card-header">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-2xl font-bold mb-2">{{ $subscription->plan->name }}</h2>
                        <p class="opacity-90">{{ $subscription->plan->description }}</p>
                    </div>
                    <span class="px-3 py-1 bg-white/20 rounded-full text-sm">
                        @if($subscription->status === 'active')
                            ✓ Actif
                        @elseif($subscription->status === 'cancelled')
                            ⚠ Annulé
                        @else
                            {{ ucfirst($subscription->status) }}
                        @endif
                    </span>
                </div>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Date de début</p>
                        <p class="font-semibold">{{ $subscription->starts_at->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Date d'expiration</p>
                        <p class="font-semibold">{{ $subscription->expires_at->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Jours restants</p>
                        <p class="font-semibold text-blue-600">{{ $subscription->daysRemaining() }} jours</p>
                    </div>
                </div>

                <div class="border-t pt-6">
                    <h3 class="font-bold mb-4">Fonctionnalités incluses</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @if($subscription->plan->features)
                            @foreach($subscription->plan->features as $feature)
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm">{{ $feature }}</span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                    <div class="border-t border-gray-200 dark:border-slate-700 mt-6 pt-6 flex flex-wrap gap-3">
                        <a href="{{ route('subscriptions.index') }}" class="btn-primary">
                            Changer de plan
                        </a>

                        @if($subscription->status === 'active')
                            <button wire:click="$set('showCancelModal', true)" class="px-4 py-2 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-lg hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors">
                                Annuler l'abonnement
                            </button>
                        @elseif($subscription->status === 'cancelled' && !$subscription->isExpired())
                            <button wire:click="reactivateSubscription" class="btn-primary">
                                Réactiver l'abonnement
                            </button>
                        @endif
                    </div>
            </div>
        </div>
        @else
            <!-- Aucun abonnement -->
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg p-6 mb-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-yellow-500 dark:text-yellow-400 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div>
                        <h3 class="font-bold text-yellow-800 dark:text-yellow-400 mb-2">Aucun abonnement actif</h3>
                        <p class="text-yellow-700 dark:text-yellow-500 mb-4">Vous n'avez pas d'abonnement actif pour le moment. Choisissez un plan pour accéder à toutes les chaînes.</p>
                        <a href="{{ route('subscriptions.index') }}" class="btn-primary">
                            Voir les plans disponibles
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Historique des abonnements -->
        <div class="card-premium p-6">
            <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Historique des abonnements</h2>
        
            @if($allSubscriptions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50 dark:bg-slate-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Plan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Début</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Fin</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Paiement</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                            @foreach($allSubscriptions as $sub)
                                <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50">
                                    <td class="px-6 py-4">
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $sub->plan->name }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $sub->starts_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $sub->expires_at->format('d/m/Y') }}
                                    </td>
                                <td class="px-6 py-4">
                                    @if($sub->status === 'active')
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-sm">Actif</span>
                                    @elseif($sub->status === 'expired')
                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-sm">Expiré</span>
                                    @elseif($sub->status === 'cancelled')
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-sm">Annulé</span>
                                    @else
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-sm">{{ ucfirst($sub->status) }}</span>
                                    @endif
                                </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        {{ ucfirst($sub->payment_method) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400 text-center py-8">Aucun historique d'abonnement</p>
            @endif
        </div>
    </div>

        <!-- Modal de confirmation d'annulation -->
        @if($showCancelModal)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white dark:bg-slate-800 rounded-lg p-6 max-w-md w-full mx-4 shadow-2xl">
                    <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Confirmer l'annulation</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        Êtes-vous sûr de vouloir annuler votre abonnement ? Vous pourrez continuer à utiliser les services jusqu'au {{ $subscription->expires_at->format('d/m/Y') }}.
                    </p>
                    <div class="flex justify-end space-x-3">
                        <button wire:click="$set('showCancelModal', false)" class="btn-secondary">
                            Non, garder mon abonnement
                        </button>
                        <button wire:click="cancelSubscription" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            Oui, annuler
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
