<?php

namespace App\Livewire;

use App\Models\Channel;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;

class ChannelList extends Component
{
    #[Url(keep: true)]
    public $search = '';
    
    #[Url(keep: true)]
    public $category = '';
    
    public $selectedChannel = null;

    /**
     * Sélectionner une chaîne et initialiser le lecteur HLS (selon Plan.md)
     */
    public function selectChannel($channelId)
    {
        $channel = Channel::with('subscriptionPlans')->find($channelId);
        
        if (!$channel || !$channel->is_active) {
            session()->flash('error', 'Cette chaîne n\'est pas disponible.');
            return;
        }
        
        // Vérifier si l'utilisateur peut accéder à cette chaîne
        $user = auth()->user();
        if ($user && !$user->canAccessChannel($channel)) {
            session()->flash('error', 'Vous devez souscrire à un plan supérieur pour accéder à cette chaîne.');
            return;
        }
        
        // Si non connecté, vérifier si c'est une chaîne gratuite
        if (!$user && !$channel->subscriptionPlans()->where('price', 0)->exists()) {
            session()->flash('error', 'Veuillez vous connecter pour accéder à cette chaîne.');
            return;
        }
        
        $this->selectedChannel = $channel;
        
        // Incrémenter le compteur de vues
        $channel->incrementViews();
    }

    /**
     * Fermer le lecteur
     */
    public function closePlayer()
    {
        $this->selectedChannel = null;
        $this->dispatch('player-closed');
    }

    /**
     * Toggle favori avec vérification auth
     */
    public function toggleFavorite($channelId)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        if ($user->favorites()->where('channel_id', $channelId)->exists()) {
            $user->favorites()->detach($channelId);
            $this->dispatch('favorite-removed', channelId: $channelId);
        } else {
            $user->favorites()->attach($channelId);
            $this->dispatch('favorite-added', channelId: $channelId);
        }
    }



    /**
     * Render avec optimisations PWA et filtrage par plan d'abonnement
     */
    public function render()
    {
        $user = auth()->user();
        $query = Channel::where('is_active', true);

        // Filtrer par plan d'abonnement de l'utilisateur
        if ($user) {
            $subscription = $user->activeSubscription;
            
            if ($subscription) {
                // Utilisateur avec abonnement actif - montrer les chaînes de son plan
                $query->whereHas('subscriptionPlans', function($q) use ($subscription) {
                    $q->where('subscription_plan_id', $subscription->subscription_plan_id);
                });
            } else {
                // Utilisateur sans abonnement - montrer uniquement les chaînes gratuites
                $query->whereHas('subscriptionPlans', function($q) {
                    $q->where('price', 0);
                });
            }
        } else {
            // Utilisateur non connecté - montrer uniquement les chaînes gratuites
            $query->whereHas('subscriptionPlans', function($q) {
                $q->where('price', 0);
            });
        }

        // Recherche optimisée
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('category', 'like', "%{$this->search}%")
                  ->orWhere('country', 'like', "%{$this->search}%");
            });
        }

        // Filtre par catégorie
        if ($this->category) {
            $query->where('category', $this->category);
        }

        // Récupérer les chaînes (pas de cache car dépend de l'utilisateur)
        $userId = $user ? $user->id : 'guest';
        $subscriptionId = $user && $user->activeSubscription ? $user->activeSubscription->subscription_plan_id : 'free';
        
        $channels = cache()->remember(
            "channels.{$userId}.{$subscriptionId}.{$this->search}.{$this->category}",
            now()->addMinutes(5),
            fn() => $query->orderBy('name')->get()
        );
        
        // Récupérer les catégories disponibles pour l'utilisateur
        $categories = cache()->remember(
            "channel.categories.{$userId}.{$subscriptionId}",
            now()->addHours(1),
            function() use ($user) {
                $query = Channel::where('is_active', true);
                
                if ($user) {
                    $subscription = $user->activeSubscription;
                    if ($subscription) {
                        $query->whereHas('subscriptionPlans', function($q) use ($subscription) {
                            $q->where('subscription_plan_id', $subscription->subscription_plan_id);
                        });
                    } else {
                        $query->whereHas('subscriptionPlans', function($q) {
                            $q->where('price', 0);
                        });
                    }
                } else {
                    $query->whereHas('subscriptionPlans', function($q) {
                        $q->where('price', 0);
                    });
                }
                
                return $query->distinct()
                    ->pluck('category')
                    ->filter()
                    ->sort();
            }
        );

        return view('livewire.channel-list', [
            'channels' => $channels,
            'categories' => $categories,
        ])->layout('components.layouts.app', ['title' => 'Chaînes en direct']);
    }
}
