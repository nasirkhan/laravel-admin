@php
$rolePermissions = $data ? $data->permissions->pluck('name')->all() : [];
$checkedPermissions = old('permissions', $rolePermissions);

$groupedPermissions = $permissions
    ->groupBy(fn($p) => Str::after($p->name, '_'))
    ->sortKeys();

$standardAccess = ['view', 'add', 'edit', 'delete', 'restore'];

$extraAccess = $permissions
    ->map(fn($p) => Str::before($p->name, '_'))
    ->unique()
    ->diff($standardAccess)
    ->sort()
    ->values()
    ->all();

$allAccess = array_merge($standardAccess, $extraAccess);
@endphp

<div class="mb-3">
    <x-cube::label for="name" :value="__('labels.backend.roles.fields.name')" required />

    @if ($name_locked ?? false)
        <input type="hidden" name="name" value="{{ optional($data)->name }}" />
        <div class="mt-1">
            <x-cube::input type="text" name="name" :value="optional($data)->name" :placeholder="__('labels.backend.roles.fields.name')" disabled />
        </div>
        <small class="text-yellow-600 dark:text-yellow-400">
            <i class="fas fa-lock"></i>
            {{ __('Role name cannot be changed because :count user(s) are assigned to this role.', ['count' => $role_users_count]) }}
        </small>
    @else
        <div class="mt-1">
            <x-cube::input type="text" name="name" :value="old('name', optional($data)->name ?? '')" :placeholder="__('labels.backend.roles.fields.name')" required />
        </div>
        <x-cube::error :messages="$errors->get('name')" class="mt-2" />
    @endif
</div>

<div class="mb-3">
    <x-cube::label :value="__('Abilities')" />
    <p class="mb-2 text-sm text-gray-600 dark:text-gray-400">{{ __("Select permissions from the list:") }}</p>

    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-2.5 font-semibold text-gray-700 dark:text-gray-300 w-44">{{ __('Module') }}</th>
                    @foreach($allAccess as $access)
                        <th class="px-3 py-2.5 font-semibold text-gray-700 dark:text-gray-300 text-center">{{ __(ucfirst($access)) }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($groupedPermissions as $moduleName => $modulePermissions)
                    <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-2.5 font-medium text-gray-700 dark:text-gray-300">
                            {{ Str::title(str_replace('_', ' ', $moduleName)) }}
                        </td>
                        @foreach($allAccess as $access)
                            @php $perm = $modulePermissions->firstWhere('name', $access . '_' . $moduleName); @endphp
                            <td class="px-3 py-2.5">
                                @if($perm)
                                    <div class="flex flex-col items-center gap-1">
                                        <x-cube::checkbox
                                            name="permissions[]"
                                            value="{{ $perm->name }}"
                                            id="permission-{{ $perm->id }}"
                                            :checked="in_array($perm->name, $checkedPermissions)"
                                            aria-label="{{ $perm->name }}"
                                        />
                                        <span class="font-mono text-xs text-gray-400 dark:text-gray-500 select-all">{{ $perm->name }}</span>
                                    </div>
                                @else
                                    <div class="flex justify-center text-gray-300 dark:text-gray-600">—</div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
