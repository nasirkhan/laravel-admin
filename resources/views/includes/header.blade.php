@php
    $notifications = optional(auth()->user())->unreadNotifications;
    $notificationsCount = optional($notifications)->count();
    $notificationsLatest = optional($notifications)->take(5);
@endphp

<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700 sm:ml-0">
    <div class="px-3 py-2 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">

            {{-- Left: hamburger + logo + breadcrumb --}}
            <div class="flex items-center gap-3">
                <button
                    data-drawer-target="default-sidebar"
                    data-drawer-toggle="default-sidebar"
                    aria-controls="default-sidebar"
                    type="button"
                    class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                >
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                {{-- Logo + brand name (matches Flowbite first shell) --}}
                <a href="{{ route('backend.dashboard') }}" class="flex items-center me-4 md:me-6">
                    <x-cube::application-logo :square="true" class="h-10 rounded fill-current text-black dark:text-white md:hidden" />
                    <x-cube::application-logo class="hidden h-10 rounded fill-current text-black dark:text-white md:block" />
                </a>

                {{-- Frontend link --}}
                <a href="{{ url('/') }}" target="_blank" title="{{ __('View Site') }}" class="flex p-2 text-gray-500 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700 focus:outline-none" aria-label="{{ __('View Site') }}">
                    <i class="fa-solid fa-up-right-from-square"></i>
                    <span class="sr-only">{{ __('View Site') }}</span>
                </a>

                {{-- Breadcrumb slot --}}
                @hasSection('breadcrumbs')
                    <nav class="hidden sm:block" aria-label="Breadcrumb">
                        @yield('breadcrumbs')
                    </nav>
                @endif
            </div>

            {{-- Right: date/clock, dark mode, notifications, user --}}
            <div class="flex items-center gap-2">                

                {{-- Live clock --}}
                <span class="hidden md:block text-sm text-gray-500 dark:text-gray-400 mr-2">
                    {{ date_today() }} &nbsp;<span id="liveClock"></span>
                </span>

                {{-- Dark mode toggle --}}
                <button
                    id="theme-toggle"
                    type="button"
                    class="p-2 text-gray-500 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700 focus:outline-none"
                    aria-label="Toggle dark mode"
                >
                    <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                    </svg>
                    <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"/>
                    </svg>
                </button>

                {{-- Language switcher --}}
                <x-cube::backend-include-menu-language />

                {{-- Notifications --}}
                <div class="relative">
                    <button
                        type="button"
                        data-dropdown-toggle="notifications-dropdown"
                        class="relative p-2 text-gray-500 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700 focus:outline-none"
                        aria-label="Notifications"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @if ($notificationsCount)
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        @endif
                    </button>

                    <div
                        id="notifications-dropdown"
                        class="hidden z-50 my-1 w-72 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600"
                    >
                        <div class="px-4 py-2 font-medium text-gray-700 dark:text-white">
                            @lang('Notifications')
                            @if ($notificationsCount)
                                <span class="ml-2 inline-flex items-center justify-center w-5 h-5 text-xs font-semibold text-white bg-red-500 rounded-full">
                                    {{ $notificationsCount }}
                                </span>
                            @endif
                        </div>
                        <ul class="py-1 divide-y divide-gray-100 dark:divide-gray-600">
                            @forelse ($notificationsLatest ?? [] as $notification)
                                @php
                                    $notificationText = $notification->data['title']
                                        ?? $notification->data['module']
                                        ?? $notification->data['message']
                                        ?? __('Notification');
                                @endphp
                                <li>
                                    <a
                                        href="{{ route('backend.notifications.show', $notification) }}"
                                        class="flex px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200"
                                    >
                                        <span class="flex-shrink-0 inline-flex items-center justify-center w-2 h-2 mt-1.5 mr-3 bg-red-500 rounded-full"></span>
                                        <span class="truncate">{{ $notificationText }}</span>
                                    </a>
                                </li>
                            @empty
                                <li class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">
                                    @lang('No new notifications')
                                </li>
                            @endforelse
                        </ul>
                        <div class="py-1">
                            <a
                                href="{{ route('backend.notifications.index') }}"
                                class="block px-4 py-2 text-sm text-center text-amber-600 hover:bg-gray-100 dark:text-amber-400 dark:hover:bg-gray-600"
                            >
                                @lang('View all notifications')
                            </a>
                        </div>
                    </div>
                </div>

                {{-- User dropdown --}}
                <div class="relative">
                    <button
                        type="button"
                        data-dropdown-toggle="user-dropdown"
                        data-dropdown-placement="bottom"
                        class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                        aria-expanded="false"
                        aria-label="User menu"
                    >
                        <span class="sr-only">Open user menu</span>
                        @php
                            $avatar = asset(auth()->user()->avatar ?? 'img/default-avatar.jpg');
                        @endphp
                        <img
                            class="w-8 h-8 rounded-full object-cover"
                            src="{{ $avatar }}"
                            alt="{{ auth()->user()->name ?? '' }}"
                            onerror="this.src='{{ asset('img/default-avatar.jpg') }}'"
                        />
                    </button>

                    <div
                        id="user-dropdown"
                        class="hidden z-50 my-1 w-56 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600"
                    >
                        <div class="px-4 py-3">
                            <span class="block text-sm font-medium text-gray-900 dark:text-white truncate">
                                {{ auth()->user()->name ?? '' }}
                            </span>
                            <span class="block text-sm text-gray-500 dark:text-gray-400 truncate">
                                {{ auth()->user()->email ?? '' }}
                            </span>
                        </div>
                        <ul class="py-1">
                            <li>
                                <a
                                    href="{{ auth()->check() ? route('backend.users.show', auth()->id()) : '#' }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600"
                                >
                                    @lang('Profile')
                                </a>
                            </li>
                            <li>
                                <a
                                    href="{{ route('frontend.index') }}"
                                    target="_blank"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600"
                                >
                                    @lang('View Site')
                                    <svg class="inline-block w-3 h-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                            </li>
                        </ul>
                        <ul class="py-1">
                            <li>
                                <a
                                    href="{{ route('logout') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600"
                                    onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();"
                                >
                                    @lang('Sign out')
                                </a>
                                <form id="admin-logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
