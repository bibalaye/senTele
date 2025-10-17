<x-layouts.app.sidebar>
    <flux:main class="min-h-screen bg-gray-50 dark:bg-slate-950 p-6">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">Finaliser votre abonnement</h1>

            @if (session('success'))
                <div class="bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Résumé du plan -->
                <div class="card-premium p-6">
                    <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Résumé de votre commande</h2>
                    
                    <div class="plan-card-header rounded-lg mb-4">
                        <h3 class="text-2xl font-bold mb-2">{{ $plan->name }}</h3>
                        <p class="text-sm opacity-90 mb-4">{{ $plan->description }}</p>
                        <div class="text-4xl font-bold">
                            {{ number_format($plan->price, 0, ',', ' ') }} XOF
                        </div>
                        <p class="text-sm mt-2">/ {{ $plan->duration_days }} jours</p>
                    </div>

                    <div class="space-y-2 mb-4">
                        @if($plan->features)
                            @foreach($plan->features as $feature)
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm">{{ $feature }}</span>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <!-- Code promo -->
                    <div class="border-t pt-4">
                        <h3 class="font-semibold mb-2">Code promo</h3>
                        
                        @if(session()->has('promo_code'))
                            <div class="bg-green-50 border border-green-200 rounded p-3 mb-2">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="font-semibold text-green-700">{{ session('promo_code')->code }}</span>
                                        <p class="text-sm text-green-600">
                                            -{{ $discount }} XOF
                                        </p>
                                    </div>
                                    <form action="{{ route('payment.promo.remove') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <form action="{{ route('payment.promo.apply') }}" method="POST" class="flex gap-2">
                                @csrf
                                <input type="text" name="code" placeholder="Entrez votre code" 
                                    class="flex-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    Appliquer
                                </button>
                            </form>
                        @endif
                    </div>

                    <!-- Total -->
                    <div class="border-t mt-4 pt-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Sous-total</span>
                            <span>{{ number_format($plan->price, 0, ',', ' ') }} XOF</span>
                        </div>
                        @if($discount > 0)
                            <div class="flex justify-between items-center mb-2 text-green-600">
                                <span>Réduction</span>
                                <span>-{{ number_format($discount, 0, ',', ' ') }} XOF</span>
                            </div>
                        @endif
                        <div class="flex justify-between items-center text-xl font-bold">
                            <span>Total</span>
                            <span>{{ number_format($finalPrice, 0, ',', ' ') }} XOF</span>
                        </div>
                    </div>
                </div>

                <!-- Formulaire de paiement -->
                <div class="card-premium p-6">
                    <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Méthode de paiement</h2>

                    <form action="{{ route('payment.process') }}" method="POST" id="paymentForm">
                        @csrf
                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">

                        <div class="space-y-4">
                            @if($finalPrice == 0)
                                <!-- Plan gratuit -->
                                <input type="hidden" name="payment_method" value="free">
                                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg p-4">
                                    <p class="text-green-700 dark:text-green-400 font-semibold">
                                        🎉 Cet abonnement est gratuit !
                                    </p>
                                    <p class="text-sm text-green-600 dark:text-green-500 mt-1">
                                        Cliquez sur "Activer" pour commencer.
                                    </p>
                                </div>
                            @else
                                <!-- Wave Money -->
                                <label class="flex items-center p-4 border border-gray-300 dark:border-slate-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
                                    <input type="radio" name="payment_method" value="wave" class="mr-3" required>
                                    <div class="flex-1">
                                        <div class="font-semibold text-gray-900 dark:text-white">Wave Money</div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">Paiement mobile via Wave</div>
                                    </div>
                                    <img src="https://wave.com/assets/img/logo.svg" alt="Wave" class="h-8">
                                </label>

                                <!-- Orange Money -->
                                <label class="flex items-center p-4 border border-gray-300 dark:border-slate-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
                                    <input type="radio" name="payment_method" value="orange_money" class="mr-3">
                                    <div class="flex-1">
                                        <div class="font-semibold text-gray-900 dark:text-white">Orange Money</div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">Paiement mobile via Orange</div>
                                    </div>
                                    <span class="text-2xl">🟠</span>
                                </label>

                                <!-- Free Money -->
                                <label class="flex items-center p-4 border border-gray-300 dark:border-slate-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
                                    <input type="radio" name="payment_method" value="free_money" class="mr-3">
                                    <div class="flex-1">
                                        <div class="font-semibold text-gray-900 dark:text-white">Free Money</div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">Paiement mobile via Free</div>
                                    </div>
                                    <span class="text-2xl">🔵</span>
                                </label>

                                <!-- Numéro de téléphone -->
                                <div id="phoneField" class="hidden">
                                    <label class="block text-sm font-medium mb-1 text-gray-900 dark:text-white">Numéro de téléphone</label>
                                    <input type="tel" name="phone" placeholder="77 123 45 67" 
                                        class="input-modern">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Vous recevrez une notification pour confirmer le paiement
                                    </p>
                                </div>
                            @endif

                            <!-- Bouton de soumission -->
                            <button type="submit" class="btn-primary w-full">
                                @if($finalPrice == 0)
                                    Activer mon abonnement gratuit
                                @else
                                    Payer {{ number_format($finalPrice, 0, ',', ' ') }} XOF
                                @endif
                            </button>

                            <p class="text-xs text-gray-500 dark:text-gray-400 text-center">
                                En continuant, vous acceptez nos conditions d'utilisation
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </flux:main>

    @push('scripts')
    <script>
        // Afficher le champ téléphone selon la méthode sélectionnée
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const phoneField = document.getElementById('phoneField');
                if (['wave', 'orange_money', 'free_money'].includes(this.value)) {
                    phoneField.classList.remove('hidden');
                    phoneField.querySelector('input').required = true;
                } else {
                    phoneField.classList.add('hidden');
                    phoneField.querySelector('input').required = false;
                }
            });
        });
    </script>
    @endpush
</x-layouts.app.sidebar>
