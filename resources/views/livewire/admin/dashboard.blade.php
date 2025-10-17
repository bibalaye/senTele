<div class="p-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">Tableau de bord administrateur</h1>

    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="card-premium">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Utilisateurs totaux</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total_users']) }}</p>
                </div>
                <div class="bg-seetaanal-green/20 p-3 rounded-full">
                    <svg class="w-8 h-8 text-seetaanal-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                <span class="text-seetaanal-green font-semibold">{{ $stats['active_users'] }}</span> actifs
            </p>
        </div>

        <div class="card-premium">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Abonnements actifs</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['active_subscriptions']) }}</p>
                </div>
                <div class="bg-seetaanal-green/20 p-3 rounded-full">
                    <svg class="w-8 h-8 text-seetaanal-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                <span class="text-red-500 font-semibold">{{ $stats['expired_subscriptions'] }}</span> expirés
            </p>
        </div>

        <div class="card-premium">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Revenus du mois</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['monthly_revenue'], 0, ',', ' ') }}</p>
                </div>
                <div class="bg-seetaanal-green/20 p-3 rounded-full">
                    <svg class="w-8 h-8 text-seetaanal-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">XOF</p>
        </div>

        <div class="card-premium">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Chaînes actives</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['active_channels']) }}</p>
                </div>
                <div class="bg-seetaanal-green/20 p-3 rounded-full">
                    <svg class="w-8 h-8 text-seetaanal-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                sur {{ $stats['total_channels'] }} total
            </p>
        </div>
    </div>

    <!-- Chaînes populaires -->
    <div class="card-premium">
        <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Chaînes les plus regardées</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="text-left py-3 px-4 text-gray-700 dark:text-gray-300">Chaîne</th>
                        <th class="text-left py-3 px-4 text-gray-700 dark:text-gray-300">Catégorie</th>
                        <th class="text-right py-3 px-4 text-gray-700 dark:text-gray-300">Vues</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stats['popular_channels'] as $channel)
                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            <td class="py-3 px-4">
                                <div class="flex items-center">
                                    @if($channel->logo)
                                        <img src="{{ $channel->logo }}" alt="{{ $channel->name }}" class="w-10 h-10 rounded mr-3">
                                    @endif
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $channel->name }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="badge-success">
                                    {{ $channel->category }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right font-semibold text-gray-900 dark:text-white">
                                {{ number_format($channel->watch_history_count) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-8 text-center text-gray-500 dark:text-gray-400">
                                Aucune donnée disponible
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
