<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->currentLocale()) }}"
    dir="{{ function_exists('language_direction') ? language_direction() : 'ltr' }}"
    class="{{ \Illuminate\Support\Facades\Cookie::get('color-theme', 'light') === 'dark' ? 'dark' : '' }}"
>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}" />
        <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}" />
        <link rel="apple-touch-icon" href="{{ asset('img/favicon.png') }}" sizes="76x76" />

        <title>@yield('title', config('admin.name')) | {{ config('app.name') }}</title>

        <script>
            // Apply dark mode class before first paint to avoid FOUC
            (function () {
                const stored = localStorage.getItem('color-theme');
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (stored === 'dark' || (!stored && prefersDark)) {
                    document.documentElement.classList.add('dark');
                }
            })();
        </script>

        @vite(['resources/css/app-backend.css', 'resources/js/app-backend.js'])

        @if (class_exists(\Nasirkhan\LaravelCube\CubeServiceProvider::class))
            <x-cube::google-analytics />
        @endif

        @stack('styles')
        @stack('after-styles')

        @livewireStyles
    </head>

    <body class="bg-gray-50 dark:bg-gray-900 antialiased">

        {{-- Sidebar --}}
        @include('admin::includes.sidebar')

        {{-- Top header --}}
        @include('admin::includes.header')

        {{-- Main content area --}}
        <div class="p-4 sm:ml-64 pt-18">
            {{-- Flash messages --}}
            @include('flash::message')

            {{-- Pending migration warnings from modules --}}
            @if (!empty($pendingMigrationWarnings))
                <div class="mb-4 rounded-lg border border-yellow-200 bg-yellow-50 p-4 text-sm dark:border-yellow-800 dark:bg-yellow-900/20">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-triangle-exclamation mt-0.5 shrink-0 text-yellow-600 dark:text-yellow-400"></i>
                        <div>
                            <p class="font-semibold text-yellow-800 dark:text-yellow-300">{{ __('Pending database migrations') }}</p>
                            <ul class="mt-1 list-inside list-disc space-y-0.5 text-yellow-700 dark:text-yellow-400">
                                @foreach ($pendingMigrationWarnings as $warning)
                                    <li>{!! $warning !!}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Validation errors --}}
            @includeIf('backend.includes.errors')

            {{-- Page content --}}
            @yield('content')
        </div>

        @livewireScripts

        @stack('scripts')
        @stack('after-scripts')
    </body>
</html>
