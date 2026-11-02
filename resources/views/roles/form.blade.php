@php
$rolePermissions = $data ? $data->permissions->pluck('name')->all() : [];
$checkedPermissions = old('permissions', $rolePermissions);
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

    @include('admin::includes.permissions-table', ['permissions' => $permissions, 'checkedPermissions' => $checkedPermissions])
</div>
