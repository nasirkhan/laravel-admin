<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="flex items-center gap-4 p-5">
            <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-xl bg-blue-600 text-white shadow-sm">
                <i class="fa-solid fa-newspaper text-lg"></i>
            </div>
            <div class="min-w-0">
                <div class="text-3xl font-bold text-gray-900 dark:text-white leading-none">{{ $postsCount }}</div>
                <div class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mt-1">@lang('Posts')</div>
            </div>
        </div>
        <div class="border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 px-5 py-2.5">
            <a href="{{ route('backend.posts.index') }}" class="flex items-center justify-between text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors group">
                <span class="text-xs font-semibold">@lang('View all posts')</span>
                <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-0.5 transition-transform"></i>
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="flex items-center gap-4 p-5">
            <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-xl bg-emerald-500 text-white shadow-sm">
                <i class="fa-solid fa-folder-open text-lg"></i>
            </div>
            <div class="min-w-0">
                <div class="text-3xl font-bold text-gray-900 dark:text-white leading-none">{{ $categoriesCount }}</div>
                <div class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mt-1">@lang('Categories')</div>
            </div>
        </div>
        <div class="border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 px-5 py-2.5">
            <a href="{{ route('backend.categories.index') }}" class="flex items-center justify-between text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300 transition-colors group">
                <span class="text-xs font-semibold">@lang('View all categories')</span>
                <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-0.5 transition-transform"></i>
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="flex items-center gap-4 p-5">
            <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-xl bg-violet-600 text-white shadow-sm">
                <i class="fa-solid fa-tags text-lg"></i>
            </div>
            <div class="min-w-0">
                <div class="text-3xl font-bold text-gray-900 dark:text-white leading-none">{{ $tagsCount }}</div>
                <div class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mt-1">@lang('Tags')</div>
            </div>
        </div>
        <div class="border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 px-5 py-2.5">
            <a href="{{ route('backend.tags.index') }}" class="flex items-center justify-between text-violet-600 hover:text-violet-700 dark:text-violet-400 dark:hover:text-violet-300 transition-colors group">
                <span class="text-xs font-semibold">@lang('View all tags')</span>
                <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-0.5 transition-transform"></i>
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="flex items-center gap-4 p-5">
            <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-xl bg-indigo-600 text-white shadow-sm">
                <i class="fa-solid fa-users text-lg"></i>
            </div>
            <div class="min-w-0">
                <div class="text-3xl font-bold text-gray-900 dark:text-white leading-none">{{ $usersCount }}</div>
                <div class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mt-1">@lang('Users')</div>
            </div>
        </div>
        <div class="border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 px-5 py-2.5">
            <a href="{{ route('backend.users.index') }}" class="flex items-center justify-between text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors group">
                <span class="text-xs font-semibold">@lang('View all users')</span>
                <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-0.5 transition-transform"></i>
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="flex items-center gap-4 p-5">
            <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-xl bg-rose-600 text-white shadow-sm">
                <i class="fa-solid fa-shield-halved text-lg"></i>
            </div>
            <div class="min-w-0">
                <div class="text-3xl font-bold text-gray-900 dark:text-white leading-none">{{ $rolesCount }}</div>
                <div class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mt-1">@lang('Roles')</div>
            </div>
        </div>
        <div class="border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 px-5 py-2.5">
            <a href="{{ route('backend.roles.index') }}" class="flex items-center justify-between text-rose-600 hover:text-rose-700 dark:text-rose-400 dark:hover:text-rose-300 transition-colors group">
                <span class="text-xs font-semibold">@lang('View all roles')</span>
                <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-0.5 transition-transform"></i>
            </a>
        </div>
    </div>

</div>
