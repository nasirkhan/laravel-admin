<div>
    <x-cube::lw-table :rows="$users" search-placeholder="{{ __('Search by name, username or email…') }}">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <x-cube::lw-table-th column="name" :sort-col="$sortCol" :sort-dir="$sortDir">
                    {{ __('labels.backend.users.fields.name') }}
                </x-cube::lw-table-th>
                <x-cube::lw-table-th column="username" :sort-col="$sortCol" :sort-dir="$sortDir">
                    {{ __('labels.backend.users.fields.username') }}
                </x-cube::lw-table-th>
                <x-cube::lw-table-th column="email" :sort-col="$sortCol" :sort-dir="$sortDir">
                    {{ __('labels.backend.users.fields.email') }}
                </x-cube::lw-table-th>
                <x-cube::lw-table-th>
                    {{ __('labels.backend.users.fields.roles') }}
                </x-cube::lw-table-th>
                <x-cube::lw-table-th class="text-right">
                    {{ __('labels.backend.action') }}
                </x-cube::lw-table-th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                        <a
                            href="{{ route('backend.users.show', $user->id) }}"
                            class="hover:text-blue-600 dark:hover:text-blue-400"
                        >{{ $user->name }}</a>
                    </td>
                    <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $user->username }}</td>
                    <td class="px-4 py-3">{{ $user->email }}</td>
                    <td class="px-4 py-3">
                        @if ($user->getRoleNames()->count() > 0)
                            <ul class="space-y-1">
                                @foreach ($user->getRoleNames() as $role)
                                    <li class="flex items-center gap-1.5 text-xs">
                                        <i class="fa-solid fa-user-shield fa-fw text-gray-400 shrink-0"></i>
                                        {{ ucwords($role) }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end flex-wrap">
                            <x-cube::backend-button-show
                                route="{{ route('backend.users.show', $user) }}"
                                title="{{ __('labels.backend.show') }}"
                            />

                            @can('edit_users')
                                <x-cube::backend-button-edit
                                    route="{{ route('backend.users.edit', $user) }}"
                                    title="{{ __('labels.backend.edit') }}"
                                />

                                <a
                                    href="{{ route('backend.users.changePassword', $user) }}"
                                    class="inline-flex items-center justify-center size-9 p-2 rounded-lg m-1 cursor-pointer font-medium text-white bg-sky-500 hover:bg-sky-600"
                                    title="{{ __('labels.backend.changePassword') }}"
                                    aria-label="{{ __('labels.backend.changePassword') }}"
                                ><i class="fas fa-key fa-fw" aria-hidden="true"></i></a>

                                @if ($user->status != 2 && $user->id != 1)
                                    <a
                                        href="{{ route('backend.users.block', $user) }}"
                                        class="inline-flex items-center justify-center size-9 p-2 rounded-lg m-1 cursor-pointer font-medium text-white bg-red-600 hover:bg-red-700"
                                        data-method="PATCH"
                                        data-token="{{ csrf_token() }}"
                                        data-confirm="@lang('Are you sure?')"
                                        title="{{ __('labels.backend.block') }}"
                                        aria-label="{{ __('labels.backend.block') }}"
                                    ><i class="fas fa-ban fa-fw" aria-hidden="true"></i></a>
                                @endif

                                @if ($user->status == 2)
                                    <a
                                        href="{{ route('backend.users.unblock', $user) }}"
                                        class="inline-flex items-center justify-center size-9 p-2 rounded-lg m-1 cursor-pointer font-medium text-white bg-sky-500 hover:bg-sky-600"
                                        data-method="PATCH"
                                        data-token="{{ csrf_token() }}"
                                        data-confirm="@lang('Are you sure?')"
                                        title="{{ __('labels.backend.unblock') }}"
                                        aria-label="{{ __('labels.backend.unblock') }}"
                                    ><i class="fas fa-check fa-fw" aria-hidden="true"></i></a>
                                @endif

                                @if ($user->id != 1)
                                    <a
                                        href="{{ route('backend.users.destroy', $user) }}"
                                        class="inline-flex items-center justify-center size-9 p-2 rounded-lg m-1 cursor-pointer font-medium text-white bg-red-600 hover:bg-red-700"
                                        data-method="DELETE"
                                        data-token="{{ csrf_token() }}"
                                        data-confirm="@lang('Are you sure?')"
                                        title="{{ __('labels.backend.delete') }}"
                                        aria-label="{{ __('labels.backend.delete') }}"
                                    ><i class="fas fa-trash-alt fa-fw" aria-hidden="true"></i></a>
                                @endif

                                @if ($user->email_verified_at == null)
                                    <a
                                        href="{{ route('backend.users.emailConfirmationResend', $user->id) }}"
                                        class="inline-flex items-center justify-center size-9 p-2 rounded-lg m-1 cursor-pointer font-medium text-white bg-blue-600 hover:bg-blue-700"
                                        title="@lang('Send confirmation email')"
                                        aria-label="@lang('Send confirmation email')"
                                    ><i class="fas fa-envelope fa-fw" aria-hidden="true"></i></a>
                                @endif
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-400 dark:text-gray-500">
                        @lang('No users found.')
                    </td>
                </tr>
            @endforelse
        </tbody>
    </x-cube::lw-table>
</div>
