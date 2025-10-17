<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Gestion des utilisateurs</h1>
    </div>

    @if (session()->has('message'))
        <div class="alert-success mb-4">
            {{ session('message') }}
        </div>
    @endif

    <!-- Filtres -->
    <div class="card-premium mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <input type="text" wire:model.live="search" placeholder="Rechercher par nom ou email..." 
                    class="input-field">
            </div>
            <div>
                <select wire:model.live="filterStatus" class="input-field">
                    <option value="all">Tous les statuts</option>
                    <option value="active">Actifs</option>
                    <option value="banned">Bannis</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Liste des utilisateurs -->
    <div class="card-premium overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Utilisateur</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Abonnement</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Inscription</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-seetaanal-green flex items-center justify-center text-white font-bold">
                                    {{ $user->initials() }}
                                </div>
                                <span class="ml-3 font-medium text-gray-900 dark:text-white">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            @if($user->activeSubscription)
                                <span class="badge-success">
                                    {{ $user->activeSubscription->plan->name }}
                                </span>
                            @else
                                <span class="badge-secondary">
                                    Aucun
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($user->is_banned)
                                <span class="badge-danger">Banni</span>
                            @else
                                <span class="badge-success">Actif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                            {{ $user->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end space-x-2">
                                @if($user->is_banned)
                                    <button wire:click="unbanUser({{ $user->id }})" 
                                        class="btn-primary text-sm">
                                        Débannir
                                    </button>
                                @else
                                    <button wire:click="banUser({{ $user->id }})" 
                                        class="btn-danger text-sm">
                                        Bannir
                                    </button>
                                @endif
                                <button wire:click="deleteUser({{ $user->id }})" 
                                    wire:confirm="Êtes-vous sûr de vouloir supprimer cet utilisateur ?"
                                    class="btn-secondary text-sm">
                                    Supprimer
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            Aucun utilisateur trouvé
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>
