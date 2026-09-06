<?php

namespace Nasirkhan\Admin;

use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'admin');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        if (class_exists(\Livewire\Livewire::class)) {
            \Livewire\Livewire::component('backend.users-index', \Nasirkhan\Admin\Livewire\UsersIndex::class);
            \Livewire\Livewire::component('backend.roles-index', \Nasirkhan\Admin\Livewire\RolesIndex::class);
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
