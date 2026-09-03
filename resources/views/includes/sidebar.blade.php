@php
    $notifications = optional(auth()->user())->unreadNotifications;
    $notificationsCount = optional($notifications)->count();

    // Icon SVGs keyed by name
    $icons = [
        'home'   => '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l-2 0l9 -9l9 9l-2 0" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />',
        'users'  => '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />',
        'shield' => '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3a12 12 0 0 0 8.5 3a12 12 0 0 1 -8.5 15a12 12 0 0 1 -8.5 -15a12 12 0 0 0 8.5 -3" />',
        'bell'   => '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" /><path d="M9 17v1a3 3 0 0 0 6 0v-1" />',
        'cog'    => '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />',
    ];
@endphp

<aside
    id="default-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0"
    aria-label="Sidebar"
>
    <div class="h-full flex flex-col overflow-y-auto bg-white border-r border-gray-200 dark:bg-gray-900 dark:border-gray-700">

        {{-- Brand --}}
        <div class="flex items-center justify-between px-4 py-4 border-b border-gray-200 dark:border-gray-700">
            <a href="{{ route('backend.dashboard') }}" wire:navigate class="flex items-center space-x-2">
                @if (config('admin.logo'))
                    <img src="{{ asset(config('admin.logo')) }}" alt="{{ config('admin.name') }}" class="h-8 w-auto" />
                @else
                    <span class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ config('admin.name') }}
                    </span>
                @endif
            </a>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-4 space-y-1">
            @php
                // Try dynamic menu first (module-manager), fall back to config nav
                $hasDynamicMenu = false;
                try {
                    if (class_exists(\Nasirkhan\ModuleManager\Modules\Menu\Models\Menu::class)) {
                        $hasDynamicMenu = \Nasirkhan\ModuleManager\Modules\Menu\Models\Menu::getCachedMenuData('admin-sidebar', auth()->user())->isNotEmpty();
                    }
                } catch (\Throwable) {}
            @endphp

            @if ($hasDynamicMenu)
                {{-- Dynamic menu from database --}}
                <x-cube::backend-dynamic-menu
                    location="admin-sidebar"
                    css-class="space-y-1"
                    container-tag="ul"
                />
            @else
                {{-- Config-driven nav --}}
                @foreach (config('admin.nav', []) as $item)
                    @php
                        $routeExists = \Illuminate\Support\Facades\Route::has($item['route'] ?? '');
                        $url = $routeExists ? route($item['route']) : '#';
                        $active = $routeExists && request()->routeIs($item['route']);
                        $icon = $icons[$item['icon'] ?? ''] ?? $icons['home'];
                    @endphp
                    <a
                        href="{{ $url }}"
                        wire:navigate
                        class="flex items-center px-3 py-2 text-sm font-medium rounded-lg group
                            {{ $active
                                ? 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
                                : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }}"
                    >
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            {!! $icon !!}
                        </svg>
                        <span>{{ __($item['label']) }}</span>

                        @if (($item['route'] ?? '') === 'backend.notifications.index' && $notificationsCount)
                            <span class="ml-auto inline-flex items-center justify-center w-5 h-5 text-xs font-semibold text-white bg-blue-600 rounded-full">
                                {{ $notificationsCount }}
                            </span>
                        @endif
                    </a>
                @endforeach
            @endif
        </nav>

        {{-- User info at bottom --}}
        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
            @auth
                <div class="flex items-center space-x-3">
                    @php
                        $avatar = asset(auth()->user()->avatar ?? 'img/default-avatar.jpg');
                    @endphp
                    <img
                        src="{{ $avatar }}"
                        alt="{{ auth()->user()->name }}"
                        class="w-8 h-8 rounded-full object-cover"
                        onerror="this.src='{{ asset('img/default-avatar.jpg') }}'"
                    />
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                            {{ auth()->user()->email }}
                        </p>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</aside>
