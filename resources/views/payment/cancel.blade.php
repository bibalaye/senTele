<x-layouts.app.sidebar>
    <flux:main class="min-h-screen bg-gray-50 dark:bg-slate-950 p-6">
        <div class="max-w-2xl mx-auto text-center">
            <!-- Icône d'annulation -->
            <div class="mb-6">
                <div class="w-24 h-24 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center mx-auto">
                    <svg class="w-12 h-12 text-yellow-500 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>

            <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white">Paiement annulé</h1>
            <p class="text-gray-600 dark:text-gray-400 mb-8">
                Votre paiement a été annulé. Aucun montant n'a été débité.
            </p>

            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg p-6 mb-8">
                <p class="text-yellow-800 dark:text-yellow-400">
                    Si vous avez rencontré un problème lors du paiement, n'hésitez pas à réessayer ou à contacter notre support.
                </p>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('subscriptions.index') }}" class="btn-primary">
                    Choisir un autre plan
                </a>
                <a href="{{ route('dashboard') }}" class="btn-secondary">
                    Retour au dashboard
                </a>
            </div>
        </div>
    </flux:main>
</x-layouts.app.sidebar>
