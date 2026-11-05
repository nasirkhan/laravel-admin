@extends("admin::layouts.admin")

@section("title")
    {{ $$module_name_singular->name }} - {{ __($module_action) }} - {{ __($module_title) }}
@endsection

@section("breadcrumbs")
    <x-cube::backend-breadcrumbs>
        <x-cube::backend-breadcrumb-item route='{{ route("backend.$module_name.index") }}' icon="{{ $module_icon }}">
            {{ __($module_title) }}
        </x-cube::backend-breadcrumb-item>

        <x-cube::backend-breadcrumb-item type="active">{{ __($module_action) }}</x-cube::backend-breadcrumb-item>
    </x-cube::backend-breadcrumbs>
@endsection

@section("content")
    {{-- Cancel button outside the form to prevent accidental form submission (handled by backend-layout-edit component) --}}
    <x-cube::backend-layout-edit
        :data="$$module_name_singular"
        :module_name="$module_name"
        :noForm="$is_protected_role ?? false"
    >
        <x-slot name="header">
            <x-cube::backend-section-header>
                <i class="{{ $module_icon }}"></i>
                {{ __($module_title) }}
                <small class="text-gray-500 dark:text-gray-400">{{ __($module_action) }}</small>

                <x-slot name="toolbar">
                    <x-cube::backend-button-return-back :small="true" />
                    <x-cube::backend-button-show
                        class="ml-1"
                        title="{{ __('Show') }} {{ ucwords(Str::singular($module_name)) }}"
                        route='{!! route("backend.$module_name.show", $$module_name_singular) !!}'
                        :small="true"
                    />
                </x-slot>
            </x-cube::backend-section-header>
        </x-slot>

        @if ($is_protected_role ?? false)
            <div class="flex items-center gap-3 p-4 mb-4 text-yellow-800 border border-yellow-300 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-400 dark:border-yellow-800" role="alert">
                <i class="fas fa-lock fa-lg"></i>
                <div>
                    <strong>{{ __('Protected Role') }}</strong> &mdash;
                    {{ __('This role is protected and cannot be updated.') }}
                </div>
            </div>
            <x-cube::backend-button-list
                :route="route('backend.' . $module_name . '.index')"
                :title="__('Back to Roles')"
            >{{ __('Back to Roles') }}</x-cube::backend-button-list>
        @else
            @php $name_locked = ($role_users_count ?? 0) > 0; @endphp
            @include('admin::roles.form', ['data' => $$module_name_singular])
        @endif
    </x-cube::backend-layout-edit>
@endsection
