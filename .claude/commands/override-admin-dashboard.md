Override the `admin.dashboard` Livewire component and its view so the host application can add custom stat cards to the backend dashboard.

## What to do

Follow these steps in order. Do not skip any step.

### 1. Discover what Eloquent models exist in this application

Scan `app/Models/` and list every model class found. These are candidates for new stat cards.

### 2. Ask the user which models to add as stat cards

Present the discovered models and ask the user:
- Which models should get a stat card on the dashboard?
- For each selected model, what label should the card show? (e.g. "Orders", "Products")
- What colour should each card use? Options: `blue`, `emerald`, `violet`, `indigo`, `rose`, `amber`, `teal`, `orange`
- Should each card link to a backend route? If so, what is the route name?

Wait for the user's answers before proceeding.

### 3. Create the extended Livewire component

Run:

```bash
php artisan make:livewire Backend/AdminDashboard --class --no-interaction
```

Then replace the generated class body so it extends the package base class and mounts the requested model counts. Follow this pattern exactly:

```php
namespace App\Livewire\Backend;

use Nasirkhan\Admin\Livewire\AdminDashboard as BaseAdminDashboard;

class AdminDashboard extends BaseAdminDashboard
{
    // One public int property per new model
    public int $ordersCount;

    public function mount(): void
    {
        parent::mount(); // preserves existing counts from the package

        // One line per new model
        $this->ordersCount = \App\Models\Order::count();
    }

    public function render()
    {
        return view('admin::livewire.dashboard');
    }
}
```

Only add properties and mount lines for the models the user selected.

### 4. Register the new component alias in AppServiceProvider

Open `app/Providers/AppServiceProvider.php`. In the `boot()` method, add:

```php
\Livewire\Livewire::component('admin.dashboard', \App\Livewire\Backend\AdminDashboard::class);
```

Add the call after any existing code in `boot()`. Do not add a `use` import for `Livewire` if one already exists — check first.

### 5. Publish the dashboard view

Run:

```bash
php artisan vendor:publish --tag=admin-views --no-interaction
```

If the views are already published (files exist in `resources/views/vendor/admin/`), skip this step and inform the user.

### 6. Add the new stat cards to the published view

Open `resources/views/vendor/admin/livewire/dashboard.blade.php`.

For each model the user selected, append a new card block inside the existing `<div class="grid ...">` wrapper. Use this card template — substitute colour, icon, variable name, label, and route:

```blade
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="flex items-center gap-4 p-5">
        <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-xl bg-{color}-600 text-white shadow-sm">
            <i class="fa-solid fa-{icon} text-lg"></i>
        </div>
        <div class="min-w-0">
            <div class="text-3xl font-bold text-gray-900 dark:text-white leading-none">{{ ${variableName} }}</div>
            <div class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mt-1">@lang('{Label}')</div>
        </div>
    </div>
    <div class="border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 px-5 py-2.5">
        <a href="{{ route('{route.name}') }}" class="flex items-center justify-between text-{color}-600 hover:text-{color}-700 dark:text-{color}-400 dark:hover:text-{color}-300 transition-colors group">
            <span class="text-xs font-semibold">@lang('View all {label}')</span>
            <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-0.5 transition-transform"></i>
        </a>
    </div>
</div>
```

Also update the grid wrapper class to match the new total card count:
- 1 card: `grid-cols-1`
- 2 cards: `grid-cols-2`
- 3 cards: `grid-cols-1 sm:grid-cols-2 lg:grid-cols-3`
- 4 cards: `grid-cols-2 lg:grid-cols-4`
- 5+ cards: `grid-cols-2 lg:grid-cols-5`

### 7. Run Pint to fix formatting

```bash
vendor/bin/pint --dirty --format agent
```

### 8. Confirm what was done

Tell the user:
- The extended component class created (`app/Livewire/Backend/AdminDashboard.php`)
- The alias registered in `AppServiceProvider`
- The view published and updated
- Which new cards were added and which variables they use
- A reminder to run `npm run build` or `npm run dev` if the dashboard styles do not appear
