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

Two Livewire components power the index tables:

- `backend.users-index` → `Nasirkhan\Admin\Livewire\UsersIndex`
- `backend.roles-index` → `Nasirkhan\Admin\Livewire\RolesIndex`

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
