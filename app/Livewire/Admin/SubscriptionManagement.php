<?php

namespace App\Livewire\Admin;

use App\Models\SubscriptionPlan;
use Livewire\Component;

class SubscriptionManagement extends Component
{
    public $plans;
    public $editingPlan = null;
    
    public $name = '';
    public $slug = '';
    public $description = '';
    public $price = 0;
    public $duration_days = 30;
    public $max_devices = 1;
    public $features = [];
    public $is_active = true;

    public function mount()
    {
        $this->loadPlans();
    }

    public function loadPlans()
    {
        $this->plans = SubscriptionPlan::withCount('activeSubscriptions')
            ->orderBy('sort_order')
            ->get();
    }

    public function editPlan($planId)
    {
        $plan = SubscriptionPlan::findOrFail($planId);
        $this->editingPlan = $plan->id;
        $this->name = $plan->name;
        $this->slug = $plan->slug;
        $this->description = $plan->description;
        $this->price = $plan->price;
        $this->duration_days = $plan->duration_days;
        $this->max_devices = $plan->max_devices;
        $this->features = $plan->features ?? [];
        $this->is_active = $plan->is_active;
    }

    public function savePlan()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:subscription_plans,slug,' . $this->editingPlan,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:1',
            'max_devices' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['features'] = $this->features;

        if ($this->editingPlan) {
            SubscriptionPlan::findOrFail($this->editingPlan)->update($validated);
        } else {
            SubscriptionPlan::create($validated);
        }

        $this->reset(['editingPlan', 'name', 'slug', 'description', 'price', 'duration_days', 'max_devices', 'features', 'is_active']);
        $this->loadPlans();
        session()->flash('message', 'Plan enregistré avec succès.');
    }

    public function deletePlan($planId)
    {
        SubscriptionPlan::findOrFail($planId)->delete();
        $this->loadPlans();
        session()->flash('message', 'Plan supprimé avec succès.');
    }

    public function render()
    {
        return view('livewire.admin.subscription-management');
    }
}
