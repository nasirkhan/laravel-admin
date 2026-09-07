<?php

namespace Nasirkhan\Admin\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BackendBaseController extends Controller
{
    public $module_title;

    public $module_name;

    public $module_path;

    public $module_icon;

    public $module_model;

    public function __construct()
    {
        $this->module_title = 'Modules';
        $this->module_name = 'modules';
        $this->module_path = 'backend';
        $this->module_icon = 'fas fa-tags';
        $this->module_model = "App\Models\BaseModel";
    }

    /**
     * @return array{module_title: string, module_name: string, module_path: string, module_icon: string, module_model: string, module_name_singular: string}
     */
    protected function moduleContext(): array
    {
        return [
            'module_title' => $this->module_title,
            'module_name' => $this->module_name,
            'module_path' => $this->module_path,
            'module_icon' => $this->module_icon,
            'module_model' => $this->module_model,
            'module_name_singular' => Str::singular($this->module_name),
        ];
    }

    public function index(): View
    {
        extract($this->moduleContext());

        $module_action = 'List';

        $$module_name = $module_model::paginate(15);

        logUserAccess($module_title.' '.$module_action);

        return view(
            view: "{$module_path}.{$module_name}.index_datatable",
            data: compact('module_title', 'module_name', "{$module_name}", 'module_icon', 'module_name_singular', 'module_action')
        );
    }

    public function create(): View
    {
        extract($this->moduleContext());

        $module_action = 'Create';

        logUserAccess($module_title.' '.$module_action);

        return view(
            view: "{$module_path}.{$module_name}.create",
            data: compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_name_singular', 'module_action')
        );
    }

    public function store(Request $request): RedirectResponse
    {
        extract($this->moduleContext());

        $module_action = 'Store';

        $$module_name_singular = DB::transaction(function () use ($module_model, $request) {
            return $module_model::create($request->all());
        });

        flash("New '".Str::singular($module_title)."' Added")->success()->important();

        logUserAccess($module_title.' '.$module_action.' | Id: '.$$module_name_singular->id);

        return redirect("admin/{$module_name}");
    }

    public function show($id): View
    {
        extract($this->moduleContext());

        $module_action = 'Show';

        $$module_name_singular = $module_model::findOrFail($id);

        logUserAccess($module_title.' '.$module_action.' | Id: '.$$module_name_singular->id);

        return view(
            view: "{$module_path}.{$module_name}.show",
            data: compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_name_singular', 'module_action', "{$module_name_singular}")
        );
    }

    public function edit($id): View
    {
        extract($this->moduleContext());

        $module_action = 'Edit';

        $$module_name_singular = $module_model::findOrFail($id);

        logUserAccess($module_title.' '.$module_action.' | Id: '.$$module_name_singular->id);

        return view(
            view: "{$module_path}.{$module_name}.edit",
            data: compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular', "{$module_name_singular}")
        );
    }

    public function update(Request $request, $id): RedirectResponse
    {
        extract($this->moduleContext());

        $module_action = 'Update';

        $$module_name_singular = DB::transaction(function () use ($module_model, $request, $id) {
            $record = $module_model::findOrFail($id);
            $record->update($request->all());

            return $record;
        });

        flash(Str::singular($module_title)."' Updated Successfully")->success()->important();

        logUserAccess($module_title.' '.$module_action.' | Id: '.$$module_name_singular->id);

        return redirect()->route("backend.{$module_name}.show", $$module_name_singular->id);
    }

    public function destroy($id): RedirectResponse
    {
        extract($this->moduleContext());

        $module_action = 'destroy';

        $$module_name_singular = DB::transaction(function () use ($module_model, $id) {
            $record = $module_model::findOrFail($id);
            $record->delete();

            return $record;
        });

        flash(label_case($module_name_singular).' Deleted Successfully!')->success()->important();

        logUserAccess($module_title.' '.$module_action.' | Id: '.$$module_name_singular->id);

        return redirect("admin/{$module_name}");
    }

    public function trashed(): View
    {
        extract($this->moduleContext());

        $module_action = 'Trash List';

        $$module_name = $module_model::onlyTrashed()->orderBy('deleted_at', 'desc')->paginate();

        logUserAccess($module_title.' '.$module_action);

        return view(
            view: "{$module_path}.{$module_name}.trash",
            data: compact('module_title', 'module_name', 'module_path', "{$module_name}", 'module_icon', 'module_name_singular', 'module_action')
        );
    }

    public function restore($id): RedirectResponse
    {
        extract($this->moduleContext());

        $module_action = 'Restore';

        $$module_name_singular = DB::transaction(function () use ($module_model, $id) {
            $record = $module_model::withTrashed()->findOrFail($id);
            $record->restore();

            return $record;
        });

        flash(label_case($module_name_singular).' Data Restored Successfully!')->success()->important();

        logUserAccess($module_title.' '.$module_action.' | Id: '.$$module_name_singular->id);

        return redirect("admin/{$module_name}");
    }
}
