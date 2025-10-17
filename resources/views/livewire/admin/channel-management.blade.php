<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Gestion des chaînes</h1>
        <button wire:click="$set('editingChannel', 0)" class="btn-primary">
            + Ajouter une chaîne
        </button>
    </div>

    @if (session()->has('message'))
        <div class="alert-success mb-4">
            {{ session('message') }}
        </div>
    @endif

    <!-- Modal d'édition -->
    @if($editingChannel !== null)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-900 rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">
                    {{ $editingChannel ? 'Modifier' : 'Ajouter' }} une chaîne
                </h2>
                
                <form wire:submit="saveChannel">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Nom *</label>
                            <input type="text" wire:model="name" class="input-field" required>
                            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">URL du logo</label>
                            <input type="url" wire:model="logo" class="input-field">
                            @error('logo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Pays</label>
                            <input type="text" wire:model="country" class="input-field">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Catégorie *</label>
                            <select wire:model="category" class="input-field" required>
                                <option value="">Sélectionner...</option>
                                <option value="Sports">Sports</option>
                                <option value="Actualités">Actualités</option>
                                <option value="Divertissement">Divertissement</option>
                                <option value="Films">Films</option>
                                <option value="Musique">Musique</option>
                                <option value="Documentaires">Documentaires</option>
                                <option value="Enfants">Enfants</option>
                                <option value="Général">Général</option>
                            </select>
                            @error('category') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">URL du flux (M3U8) *</label>
                            <input type="url" wire:model="stream_url" class="input-field" required>
                            @error('stream_url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Plans d'abonnement</label>
                            <div class="space-y-2">
                                @foreach($plans as $plan)
                                    <label class="flex items-center text-gray-700 dark:text-gray-300">
                                        <input type="checkbox" wire:model="selectedPlans" value="{{ $plan->id }}" class="mr-2 text-seetaanal-green focus:ring-seetaanal-green">
                                        <span>{{ $plan->name }} ({{ $plan->formatted_price }})</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" wire:model="is_active" class="mr-2 text-seetaanal-green focus:ring-seetaanal-green">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Chaîne active</label>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" wire:click="$set('editingChannel', null)" 
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

    <!-- Filtres -->
    <div class="card-premium mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="text" wire:model.live="search" placeholder="Rechercher..." 
                class="input-field">
            
            <select wire:model.live="filterCategory" class="input-field">
                <option value="all">Toutes les catégories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>

            <select wire:model.live="filterStatus" class="input-field">
                <option value="all">Tous les statuts</option>
                <option value="active">Actives</option>
                <option value="inactive">Inactives</option>
            </select>
        </div>
    </div>

    <!-- Liste des chaînes -->
    <div class="card-premium overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Chaîne</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Catégorie</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Plans</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Statut</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($channels as $channel)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($channel->logo)
                                    <img src="{{ $channel->logo }}" alt="{{ $channel->name }}" class="w-12 h-12 rounded mr-3">
                                @endif
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $channel->name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $channel->country }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="badge-success">
                                {{ $channel->category }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach($channel->subscriptionPlans as $plan)
                                    <span class="badge-secondary text-xs">
                                        {{ $plan->name }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <button wire:click="toggleStatus({{ $channel->id }})" 
                                class="px-2 py-1 rounded text-sm {{ $channel->is_active ? 'badge-success' : 'badge-danger' }}">
                                {{ $channel->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end space-x-2">
                                <button wire:click="editChannel({{ $channel->id }})" 
                                    class="btn-primary text-sm">
                                    Modifier
                                </button>
                                <button wire:click="deleteChannel({{ $channel->id }})" 
                                    wire:confirm="Supprimer cette chaîne ?"
                                    class="btn-danger text-sm">
                                    Supprimer
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            Aucune chaîne trouvée
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $channels->links() }}
    </div>
</div>
