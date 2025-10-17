<?php

namespace App\Livewire;

use App\Models\SubscriptionPlan;
use Livewire\Component;

class SubscriptionPlans extends Component
{
    public $currentSubscription = null;

    public function mount()
    {
        $this->currentSubscription = auth()->user()->activeSubscription;
    }

    public function selectPlan($planId)
    {
        return redirect()->route('payment.checkout', ['plan' => $planId]);
    }

    public function render()
    {
        $plans = SubscriptionPlan::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('livewire.subscription-plans', compact('plans'));
    }
}
