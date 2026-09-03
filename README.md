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
