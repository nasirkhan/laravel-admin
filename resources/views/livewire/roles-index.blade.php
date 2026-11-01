<div>
    <x-cube::lw-table :rows="$roles" search-placeholder="{{ __('Search by name…') }}">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <x-cube::lw-table-th column="name" :sort-col="$sortCol" :sort-dir="$sortDir">
                    {{ __('labels.backend.roles.fields.name') }}
                </x-cube::lw-table-th>
                <x-cube::lw-table-th>
                    {{ __('labels.backend.roles.fields.permissions') }}
                </x-cube::lw-table-th>
                <x-cube::lw-table-th>
                    {{ __('labels.backend.roles.fields.users_count') }}
                </x-cube::lw-table-th>
                <x-cube::lw-table-th class="text-right">
                    {{ __('labels.backend.action') }}
                </x-cube::lw-table-th>
            </tr>
        </thead>
        <tbody>
            @forelse ($roles as $role)
                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                        <a
                            href="{{ route('backend.roles.show', $role) }}"
                            class="hover:text-blue-600 dark:hover:text-blue-400"
                        >{{ ucwords($role->name) }}</a>
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium text-white bg-indigo-600 rounded-full">
                            {{ $role->permissions_count }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-medium text-white bg-blue-600 rounded-full">
                            {{ $role->users_count }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end flex-wrap">
                            <x-cube::backend-button-show
                                route="{{ route('backend.roles.show', $role) }}"
                                title="{{ __('labels.backend.show') }}"
                            />

                            @can('edit_roles')
                                <x-cube::backend-button-edit
                                    route="{{ route('backend.roles.edit', $role) }}"
                                    title="{{ __('labels.backend.edit') }}"
                                />
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-400 dark:text-gray-500">
                        @lang('No roles found.')
                    </td>
                </tr>
            @endforelse
        </tbody>
    </x-cube::lw-table>
</div>
