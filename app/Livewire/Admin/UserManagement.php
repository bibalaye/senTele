<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = 'all';
    public $selectedUser = null;
    public $banReason = '';

    protected $queryString = ['search', 'filterStatus'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function banUser($userId, $reason = 'Violation des conditions d\'utilisation')
    {
        $user = User::findOrFail($userId);
        $user->update([
            'is_banned' => true,
            'banned_at' => now(),
            'banned_reason' => $reason,
        ]);

        $user->subscriptions()->where('status', 'active')->update(['status' => 'cancelled']);

        session()->flash('message', 'Utilisateur banni avec succès.');
    }

    public function unbanUser($userId)
    {
        User::findOrFail($userId)->update([
            'is_banned' => false,
            'banned_at' => null,
            'banned_reason' => null,
        ]);

        session()->flash('message', 'Utilisateur débanni avec succès.');
    }

    public function deleteUser($userId)
    {
        User::findOrFail($userId)->delete();
        session()->flash('message', 'Utilisateur supprimé avec succès.');
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%"))
            ->when($this->filterStatus === 'banned', fn($q) => $q->where('is_banned', true))
            ->when($this->filterStatus === 'active', fn($q) => $q->where('is_banned', false))
            ->with(['activeSubscription.plan'])
            ->latest()
            ->paginate(20);

        return view('livewire.admin.user-management', compact('users'));
    }
}
