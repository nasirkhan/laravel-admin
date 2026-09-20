@extends("admin::layouts.admin")

@section("title")
    {{ __($module_action) }} {{ __($module_title) }}
@endsection

@section("breadcrumbs")
    <x-cube::backend-breadcrumbs>
        <x-cube::backend-breadcrumb-item type="active" icon="{{ $module_icon }}">
            {{ __($module_title) }}
        </x-cube::backend-breadcrumb-item>
    </x-cube::backend-breadcrumbs>
@endsection

@section("content")
    {{-- Notifications Livewire component --}}
    <livewire:admin.notifications />

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-4">
        <div class="p-6">
            <x-cube::backend-section-header>
                <i class="{{ $module_icon }}"></i>
                {{ __($module_title) }}
                @if ($unread_notifications_count)
                    (
                    @lang(":count unread", ["count" => $unread_notifications_count])
                    )
                @endif

                <small class="text-gray-500 dark:text-gray-400">{{ __($module_action) }}</small>

                <x-slot name="toolbar">
                    <x-cube::button-link
                        :href="route('backend.' . $module_name . '.markAllAsRead')"
                        variant="success"
                        :title="__('Mark all as read')"
                    >
                        <i class="fas fa-check-square fa-fw" aria-hidden="true"></i>&nbsp;{{ __("Mark all as read") }}
                    </x-cube::button-link>
                    <x-cube::button-link
                        :href="route('backend.' . $module_name . '.deleteAll')"
                        variant="danger"
                        data-method="DELETE"
                        :data-token="csrf_token()"
                        :title="__('Delete all notifications')"
                    >
                        <i class="fas fa-trash-alt fa-fw" aria-hidden="true"></i> &nbsp;{{ __("Delete All") }}
                    </x-cube::button-link>
                </x-slot>
            </x-cube::backend-section-header>

            <div class="overflow-x-auto">
                <table id="datatable" class="w-full text-sm text-left text-gray-500 dark:text-gray-400 border-collapse">
                    <thead>
                        <tr class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <th class="px-4 py-3">
                                @lang("Text")
                            </th>
                            <th class="px-4 py-3 hidden sm:table-cell">
                                @lang("Module")
                            </th>
                            <th class="px-4 py-3 hidden sm:table-cell">
                                @lang("Updated At")
                            </th>
                            <th class="px-4 py-3 text-end">
                                @lang("Action")
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($$module_name as $module_name_singular)
                            <?php
                            $row_class = "";
                            $span_class = "";
                            if ($module_name_singular->read_at == "") {
                                $row_class = "bg-blue-50 dark:bg-blue-900/20";
                                $span_class = "font-bold";
                            }
                            ?>

                            <tr class="{{ $row_class }}">
                                <td class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                    <a href="{{ route("backend.$module_name.show", $module_name_singular->id) }}">
                                        <span class="{{ $span_class }}">
                                            {{ $module_name_singular->data["title"] ?? $module_name_singular->data["module"] ?? $module_name_singular->data["message"] ?? __("Notification") }}
                                        </span>
                                    </a>
                                </td>
                                <td class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 hidden sm:table-cell">
                                    {{ $module_name_singular->data["module"] ?? __("Notification") }}
                                </td>
                                <td class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 hidden sm:table-cell">
                                    {{ $module_name_singular->updated_at->diffForHumans() }}
                                </td>
                                <td class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 text-end">
                                    <x-cube::backend.buttons.show
                                        :route="route('backend.' . $module_name . '.show', $module_name_singular)"
                                        icon="fas fa-tv"
                                        small="true"
                                        :title="__('Show') . ' ' . ucwords(Str::singular($module_name))"
                                    />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-3">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div class="text-gray-500 dark:text-gray-400">
                    @lang("Total")
                    {{ $$module_name->total() }} {{ ucwords($module_name) }}
                </div>
                <div>
                    {!! $$module_name->render() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
