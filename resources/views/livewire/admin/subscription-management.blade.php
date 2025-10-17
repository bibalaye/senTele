<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Gestion des abonnements</h1>
        <button wire:click="$set('editingPlan', 0)" class="btn-primary">
            + Ajouter un plan
        </button>
    </div>

    @if (session()->has('message'))
        <div class="alert-success mb-4">
            {{ session('message') }}
        </div>
    @endif

    <!-- Modal d'édition -->
    @if($editingPlan !== null)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-900 rounded-lg p-6 w-full max-w-2xl">
                <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">
                    {{ $editingPlan ? 'Modifier' : 'Ajouter' }} un plan
                </h2>
                
                <form wire:submit="savePlan">
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Nom *</label>
                                <input type="text" wire:model="name" class="input-field" required>
                                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Slug *</label>
                                <input type="text" wire:model="slug" class="input-field" required>
                                @error('slug') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Description</label>
                            <textarea wire:model="description" class="input-field" rows="2"></textarea>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Prix (XOF) *</label>
                                <input type="number" wire:model="price" class="input-field" min="0" required>
                                @error('price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Durée (jours) *</label>
                                <input type="number" wire:model="duration_days" class="input-field" min="1" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Appareils max *</label>
                                <input type="number" wire:model="max_devices" class="input-field" min="1" required>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" wire:model="is_active" class="mr-2 text-seetaanal-green focus:ring-seetaanal-green">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Plan actif</label>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" wire:click="$set('editingPlan', null)" 
                            class="btn-secondary">
                            Annuler
                        </button>
                        <button type="submit" class="btn-primary">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Liste des plans -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($plans as $plan)
            <div class="card-premium overflow-hidden {{ $plan->is_active ? '' : 'opacity-50' }}">
                <div class="bg-gradient-to-r from-seetaanal-green to-green-600 p-6 text-white">
                    <h3 class="text-2xl font-bold">{{ $plan->name }}</h3>
                    <div class="mt-4">
                        <span class="text-4xl font-bold">{{ number_format($plan->price, 0, ',', ' ') }}</span>
                        <span class="text-lg">XOF</span>
                    </div>
                    <p class="text-sm mt-2">/ {{ $plan->duration_days }} jours</p>
                </div>

                <div class="p-6">
                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">{{ $plan->description }}</p>
                    
                    <div class="space-y-2 mb-4">
                        @if($plan->features)
                            @foreach($plan->features as $feature)
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-seetaanal-green mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $feature }}</span>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        <p>Max {{ $plan->max_devices }} appareil(s)</p>
                        <p class="mt-1">
                            <span class="font-semibold text-seetaanal-green">{{ $plan->active_subscriptions_count }}</span> abonnés actifs
                        </p>
                    </div>

                    <div class="flex space-x-2">
                        <button wire:click="editPlan({{ $plan->id }})" 
                            class="flex-1 btn-primary text-sm">
                            Modifier
                        </button>
                        <button wire:click="deletePlan({{ $plan->id }})" 
                            wire:confirm="Supprimer ce plan ?"
                            class="btn-danger text-sm">
                            Supprimer
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
