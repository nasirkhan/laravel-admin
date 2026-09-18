<?php

namespace Nasirkhan\Admin\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Notifications extends Component
{
    public function markAllAsRead(): void
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);
    }

    public function render()
    {
        return view('admin::livewire.notifications', [
            'unreadCount' => Auth::user()->unreadNotifications()->count(),
        ]);
    }
}
