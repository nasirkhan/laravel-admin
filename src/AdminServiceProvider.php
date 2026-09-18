<?php

namespace Nasirkhan\Admin;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Nasirkhan\Admin\Livewire\Dashboard;
use Nasirkhan\Admin\Livewire\Notifications;
use Nasirkhan\Admin\Livewire\RolesIndex;
use Nasirkhan\Admin\Livewire\UsersIndex;

class AdminServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'admin');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        if (class_exists(Livewire::class)) {
            Livewire::component('backend.dashboard', Dashboard::class);
            Livewire::component('backend.notifications', Notifications::class);
            Livewire::component('backend.users-index', UsersIndex::class);
            Livewire::component('backend.roles-index', RolesIndex::class);
        }

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/admin'),
        ], 'admin-views');

        $this->publishes([
            __DIR__.'/../config/admin.php' => config_path('admin.php'),
        ], 'admin-config');

        $this->publishes([
            __DIR__.'/../routes/web.php' => base_path('routes/admin.php'),
        ], 'admin-routes');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/admin.php', 'admin');
    }
}
