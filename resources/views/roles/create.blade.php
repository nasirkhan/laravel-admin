@extends("admin::layouts.admin")

@section("title")
    {{ __($module_action) }} {{ __($module_title) }}
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
    <x-cube::backend-layout-create>
        <x-cube::backend-section-header>
            <i class="{{ $module_icon }}"></i>
            {{ __($module_title) }}
            <small class="text-gray-500 dark:text-gray-400">{{ __($module_action) }}</small>

            <x-slot name="toolbar">
                <x-cube::backend-button-return-back :small="true" />
            </x-slot>
        </x-cube::backend-section-header>

        <form method="POST" action="{{ route("backend.roles.store") }}">
            @csrf

            @include('admin::roles.form', ['data' => null])

            <div class="mb-4">
                <x-cube::backend-button-create title="{{ __('Create') }} {{ ucwords(Str::singular($module_name)) }}">
                    {{ __("Create") }}
                </x-cube::backend-button-create>
            </div>
        </form>

        <!-- Cancel button outside the form to prevent accidental form submission -->
        <div class="flex justify-end mt-3">
            <x-cube::backend-button-cancel />
        </div>
    </x-cube::backend-layout-create>
@endsection
