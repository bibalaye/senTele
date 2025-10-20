<?php

namespace App\Livewire\Admin;

use App\Models\Channel;
use App\Models\SubscriptionPlan;
use Livewire\Component;
use Livewire\WithPagination;

class ChannelManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $filterCategory = 'all';
    public $filterStatus = 'all';
    
    public $editingChannel = null;
    public $testingChannel = null;
    public $name = '';
    public $logo = '';
    public $country = '';
    public $category = '';
    public $stream_url = '';
    public $is_active = true;
    public $selectedPlans = [];

    protected $queryString = ['search', 'filterCategory'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function editChannel($channelId)
    {
        $channel = Channel::with('subscriptionPlans')->findOrFail($channelId);
        $this->editingChannel = $channel->id;
        $this->name = $channel->name;
        $this->logo = $channel->logo;
        $this->country = $channel->country;
        $this->category = $channel->category;
        $this->stream_url = $channel->stream_url;
        $this->is_active = $channel->is_active;
        $this->selectedPlans = $channel->subscriptionPlans->pluck('id')->toArray();
    }

    public function saveChannel()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|url',
            'country' => 'nullable|string|max:100',
            'category' => 'required|string|max:100',
            'stream_url' => 'required|url',
            'is_active' => 'boolean',
        ]);

        if ($this->editingChannel) {
            $channel = Channel::findOrFail($this->editingChannel);
            $channel->update($validated);
        } else {
            $channel = Channel::create($validated);
        }

        if (!empty($this->selectedPlans)) {
            $channel->subscriptionPlans()->sync($this->selectedPlans);
        }

        $this->reset(['editingChannel', 'name', 'logo', 'country', 'category', 'stream_url', 'is_active', 'selectedPlans']);
        session()->flash('message', 'Chaîne enregistrée avec succès.');
    }

    public function deleteChannel($channelId)
    {
        Channel::findOrFail($channelId)->delete();
        session()->flash('message', 'Chaîne supprimée avec succès.');
    }

    public function toggleStatus($channelId)
    {
        $channel = Channel::findOrFail($channelId);
        $channel->update(['is_active' => !$channel->is_active]);
    }

    public function testChannel($channelId)
    {
        $this->testingChannel = Channel::findOrFail($channelId);
    }

    public function closeTestPlayer()
    {
        $this->testingChannel = null;
        $this->dispatch('test-player-closed');
    }

    public function render()
    {
        $channels = Channel::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->filterCategory !== 'all', fn($q) => $q->where('category', $this->filterCategory))
            ->when($this->filterStatus === 'active', fn($q) => $q->where('is_active', true))
            ->when($this->filterStatus === 'inactive', fn($q) => $q->where('is_active', false))
            ->with('subscriptionPlans')
            ->latest()
            ->paginate(20);

        $categories = Channel::distinct()->pluck('category');
        $plans = SubscriptionPlan::where('is_active', true)->get();

        return view('livewire.admin.channel-management', compact('channels', 'categories', 'plans'));
    }
}
