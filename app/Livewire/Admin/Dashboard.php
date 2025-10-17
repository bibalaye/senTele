<?php

namespace App\Livewire\Admin;

use App\Models\Channel;
use App\Models\User;
use App\Models\UserSubscription;
use Livewire\Component;

class Dashboard extends Component
{
    public $stats = [];

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $this->stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_banned', false)->count(),
            'banned_users' => User::where('is_banned', true)->count(),
            'active_subscriptions' => UserSubscription::active()->count(),
            'expired_subscriptions' => UserSubscription::expired()->count(),
            'monthly_revenue' => UserSubscription::active()
                ->whereMonth('user_subscriptions.created_at', now()->month)
                ->join('subscription_plans', 'user_subscriptions.subscription_plan_id', '=', 'subscription_plans.id')
                ->sum('subscription_plans.price'),
            'total_channels' => Channel::count(),
            'active_channels' => Channel::where('is_active', true)->count(),
            'popular_channels' => Channel::withCount('watchHistory')
                ->orderBy('watch_history_count', 'desc')
                ->take(10)
                ->get(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
