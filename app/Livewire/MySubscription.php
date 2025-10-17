<?php

namespace App\Livewire;

use Livewire\Component;

class MySubscription extends Component
{
    public $subscription;
    public $showCancelModal = false;

    public function mount()
    {
        $this->subscription = auth()->user()->activeSubscription;
    }

    public function cancelSubscription()
    {
        if ($this->subscription) {
            $this->subscription->update([
                'status' => 'cancelled',
                'auto_renew' => false,
            ]);

            session()->flash('message', 'Votre abonnement a été annulé. Il restera actif jusqu\'à la date d\'expiration.');
            
            $this->subscription = auth()->user()->activeSubscription;
            $this->showCancelModal = false;
        }
    }

    public function reactivateSubscription()
    {
        if ($this->subscription && $this->subscription->status === 'cancelled') {
            $this->subscription->update([
                'status' => 'active',
                'auto_renew' => true,
            ]);

            session()->flash('message', 'Votre abonnement a été réactivé avec succès.');
            
            $this->subscription = auth()->user()->activeSubscription;
        }
    }

    public function render()
    {
        $allSubscriptions = auth()->user()->subscriptions()
            ->with('plan')
            ->latest()
            ->take(10)
            ->get();

        return view('livewire.my-subscription', compact('allSubscriptions'));
    }
}
