# laravel-admin

A Tailwind CSS / Flowbite backend shell for Laravel — sidebar, header, breadcrumb layout.

This package provides the structural layout (sidebar, top navigation, main content wrapper) for a Laravel admin panel. It is designed to work alongside [laravel-cube](https://github.com/nasirkhan/laravel-cube) for UI components, but the core layout works independently.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nasirkhan/laravel-admin.svg?style=flat-square)](https://packagist.org/packages/nasirkhan/laravel-admin)
[![Total Downloads](https://img.shields.io/packagist/dt/nasirkhan/laravel-admin.svg?style=flat-square)](https://packagist.org/packages/nasirkhan/laravel-admin)
[![License](https://img.shields.io/packagist/l/nasirkhan/laravel-admin.svg?style=flat-square)](https://packagist.org/packages/nasirkhan/laravel-admin)

## Requirements

- PHP ^8.2
- Laravel 11 / 12 / 13
- Tailwind CSS v4 + Flowbite (compiled via Vite in the host application)

## Installation

```bash
composer require nasirkhan/laravel-admin
```

The service provider is auto-discovered.

## Publish assets

Publish the config file:

```bash
php artisan vendor:publish --tag=admin-config
```

Publish the views (only if you need to customise them):

```bash
php artisan vendor:publish --tag=admin-views
```

## Configuration

After publishing, edit `config/admin.php`:

```php
return [
    // Brand name shown in the sidebar and browser tab
    'name' => env('APP_NAME', 'Admin'),

    // Optional logo path relative to public/ (null = text brand)
    'logo' => null,

    // Default theme: 'system' | 'light' | 'dark'
    'theme' => 'system',

    // Fallback navigation when no dynamic menu (module-manager) is available
    'nav' => [
        [
            'label'    => 'Dashboard',
            'route'    => 'backend.dashboard',
            'icon'     => 'home',
            'children' => [],
        ],
        [
            'label'    => 'Users',
            'route'    => 'backend.users.index',
            'icon'     => 'users',
            'children' => [],
        ],
        [
            'label'    => 'Roles',
            'route'    => 'backend.roles.index',
            'icon'     => 'shield',
            'children' => [],
        ],
        [
            'label'    => 'Notifications',
            'route'    => 'backend.notifications.index',
            'icon'     => 'bell',
            'children' => [],
        ],
    ],
];
```

Supported `icon` values: `home`, `users`, `shield`, `bell`, `cog`.

## What's included

Beyond the layout shell, the package ships a complete backend for the three core resources:

| Resource | Routes (prefix `admin/`) | Controller |
|---|---|---|
| Dashboard | `GET /`, `GET /dashboard` | `BackendController` |
| Users | CRUD + block/unblock/trash/restore/change-password | `UserController` |
| Roles | CRUD + permission sync | `RolesController` |
| Notifications | index / show / mark-all-read / delete-all | `NotificationsController` |

All routes are named under the `backend.` prefix (e.g. `backend.users.index`) and protected by the `auth` and `can:view_backend` middleware.

Four Livewire components are registered by the package:

| Alias | Class |
|---|---|
| `admin.dashboard` | `Nasirkhan\Admin\Livewire\AdminDashboard` |
| `admin.notifications` | `Nasirkhan\Admin\Livewire\Notifications` |
| `backend.users-index` | `Nasirkhan\Admin\Livewire\UsersIndex` |
| `backend.roles-index` | `Nasirkhan\Admin\Livewire\RolesIndex` |

Controllers intentionally keep `use App\Models\User` and `use App\Models\Role` — models stay in the host application.

## Customising views

Published views in the host application always take precedence over the package defaults. You can override any view without touching the package.

### Publish all views at once

```bash
php artisan vendor:publish --tag=admin-views
```

Files land in `resources/views/vendor/admin/` with the same structure as the package:

```
resources/views/vendor/admin/
├── users/
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── edit.blade.php
│   ├── show.blade.php
│   ├── changePassword.blade.php
│   └── trash.blade.php
├── roles/
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── edit.blade.php
│   └── show.blade.php
├── notifications/
│   ├── index.blade.php
│   └── show.blade.php
├── includes/
│   ├── action_column.blade.php
│   ├── errors.blade.php
│   ├── show.blade.php
│   ├── user_actions.blade.php
│   └── user_roles.blade.php
└── livewire/
    ├── dashboard.blade.php
    ├── notifications.blade.php
    ├── users-index.blade.php
    └── roles-index.blade.php
```

### Publish selectively

Copy only the files you need to override. For example, to override just the user edit form:

```bash
mkdir -p resources/views/vendor/admin/users
cp vendor/nasirkhan/laravel-admin/resources/views/users/edit.blade.php \
   resources/views/vendor/admin/users/edit.blade.php
```

Laravel resolves `admin::users.edit` by looking in `resources/views/vendor/admin/` first, then falling back to the package.

### Publish everything (views + config + routes)

```bash
php artisan vendor:publish --provider="Nasirkhan\Admin\AdminServiceProvider"
```

## Adding columns to the users table

To add a new field (e.g. `phone`) to user management:

**1. Create and run a migration**

```bash
php artisan make:migration add_phone_to_users_table --table=users
php artisan migrate
```

**2. Add the column to `$fillable` in `App\Models\User`**

```php
protected $fillable = ['name', 'email', 'phone', ...];
```

**3. Publish the views you need to change** (if not already published)

```bash
php artisan vendor:publish --tag=admin-views
```

**4. Edit the published views**

- `resources/views/vendor/admin/users/create.blade.php` — add the form field
- `resources/views/vendor/admin/users/edit.blade.php` — add the field with its old/current value
- `resources/views/vendor/admin/users/show.blade.php` — add a display row

**5. Optional — make the column visible in the listing**

Publish and edit `resources/views/vendor/admin/livewire/users-index.blade.php` to add a column to the table. If the column also needs to be searchable or sortable, publish the Livewire component class by copying it into your app and re-registering it in a service provider:

```php
// In your AppServiceProvider::boot()
\Livewire\Livewire::component('backend.users-index', \App\Livewire\Admin\UsersIndex::class);
```

## Customising the Dashboard

The dashboard is powered by the `admin.dashboard` Livewire component (`AdminDashboard` class) and its view `admin::livewire.dashboard`. Both are designed to be overridden without touching the package.

### Remove the demo data

The default dashboard includes a demo data include. To remove it, publish the main dashboard view and delete the include line:

```bash
php artisan vendor:publish --tag=admin-views
```

Then open `resources/views/vendor/admin/index.blade.php` and remove:

```blade
@include("admin::includes.dashboard_demo_data")
```

### Add or change stat cards (view only)

If you only need to change the layout or copy of the cards — no new database queries — override just the Livewire view:

```bash
mkdir -p resources/views/vendor/admin/livewire
cp vendor/nasirkhan/laravel-admin/resources/views/livewire/dashboard.blade.php \
   resources/views/vendor/admin/livewire/dashboard.blade.php
```

Edit the published file. Each card follows this structure:

```blade
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="flex items-center gap-4 p-5">
        <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-xl bg-blue-600 text-white shadow-sm">
            <i class="fa-solid fa-newspaper text-lg"></i>
        </div>
        <div>
            <div class="text-3xl font-bold text-gray-900 dark:text-white leading-none">{{ $postsCount }}</div>
            <div class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mt-1">@lang('Posts')</div>
        </div>
    </div>
    <div class="border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 px-5 py-2.5">
        <a href="{{ route('backend.posts.index') }}" class="flex items-center justify-between text-blue-600 hover:text-blue-700 dark:text-blue-400 transition-colors group">
            <span class="text-xs font-semibold">@lang('View all posts')</span>
            <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-0.5 transition-transform"></i>
        </a>
    </div>
</div>
```

The grid wrapping the cards uses `grid-cols-1 sm:grid-cols-2 lg:grid-cols-5`. Adjust the last class to match the number of cards you have (e.g. `lg:grid-cols-3` for three cards).

### Add a card that needs new data (class + view)

When a new card needs a count or query that the base component does not expose, extend `AdminDashboard` and re-register the alias.

**1. Create the extended component**

```php
// app/Livewire/Admin/AdminDashboard.php
namespace App\Livewire\Admin;

use Nasirkhan\Admin\Livewire\AdminDashboard as BaseAdminDashboard;

class AdminDashboard extends BaseAdminDashboard
{
    public int $commentsCount;

    public function mount(): void
    {
        parent::mount(); // keeps the existing counts

        $this->commentsCount = \App\Models\Comment::count();
    }
}
```

**2. Re-register the Livewire alias**

Because `AppServiceProvider` boots after `AdminServiceProvider`, registering the alias again replaces the package's class:

```php
// app/Providers/AppServiceProvider.php
use Livewire\Livewire;

public function boot(): void
{
    Livewire::component('admin.dashboard', \App\Livewire\Admin\AdminDashboard::class);
}
```

**3. Override the view and add the card**

Publish the Livewire view (if you haven't already) and add a new card block that references `$commentsCount`:

```bash
mkdir -p resources/views/vendor/admin/livewire
cp vendor/nasirkhan/laravel-admin/resources/views/livewire/dashboard.blade.php \
   resources/views/vendor/admin/livewire/dashboard.blade.php
```

Then add a card for `{{ $commentsCount }}` inside the grid in the published file.

### Overriding the Notifications component

The same pattern applies to the `Notifications` component:

| What to override | Path |
|---|---|
| View only | `resources/views/vendor/admin/livewire/notifications.blade.php` |
| Class | Extend `Nasirkhan\Admin\Livewire\Notifications`, re-register `admin.notifications` |

## Usage

Extend the admin layout in your backend Blade views:

```blade
@extends('admin::layouts.admin')

@section('title', 'Dashboard')

@section('breadcrumbs')
    {{-- optional breadcrumb markup --}}
@endsection

@section('content')
    <p>Your page content goes here.</p>
@endsection
```

### Layout slots

| Section / Stack | Purpose |
|---|---|
| `title` | Browser tab / page title |
| `breadcrumbs` | Rendered inside the top navbar |
| `content` | Main page body |
| `@stack('styles')` | Extra `<style>` or `<link>` tags in `<head>` |
| `@stack('scripts')` | Extra `<script>` tags before `</body>` |

## Vite setup

The layout expects two Vite entry points compiled in the **host application**:

```js
// vite.config.js
export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app-backend.css',
                'resources/js/app-backend.js',
            ],
        }),
    ],
});
```

`app-backend.css` should import Tailwind CSS v4 and Flowbite:

```css
@import 'tailwindcss';
@import 'flowbite/src/flowbite.css';

@source '../views/backend/**/*.blade.php';
@source '../../vendor/nasirkhan/laravel-admin/resources/views/**/*.blade.php';
```

## Optional integrations

| Package | Effect |
|---|---|
| `nasirkhan/laravel-cube` | Enables language switcher, Google Analytics, and dynamic menu components |
| `nasirkhan/module-manager` | Enables database-driven dynamic sidebar navigation |

These integrations are detected at runtime — the layout degrades gracefully without them.

## Dark mode

Dark mode is toggled via a button in the top navbar and persisted in `localStorage` under the key `color-theme`. The `<html>` tag receives the `dark` class (Tailwind class-based dark mode).

## License

GPL-3.0-or-later — see [LICENSE](LICENSE).
