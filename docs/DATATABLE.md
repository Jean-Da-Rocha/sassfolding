# Datatables

The Datatables module provides a reusable, full-featured table system built on top of
[Hybridly Tables](https://hybridly.dev/guide/tables.html) (server-side) and
[Nuxt UI's UTable](https://ui.nuxt.com/components/table) (TanStack Table, client-side).

## Features

- Server-side pagination, sorting, and search
- Per-column filtering (ternary, boolean, date, select with multi-select, trashed)
- Column visibility toggle
- Row selection with shift-click range selection and bulk actions (backend-driven)
- Per-row inline actions (backend-driven, with optional confirmation modal)
- Right-click context menu
- Custom cell rendering via slots (including dynamic badge rendering)
- Loading state and empty state

## Backend: Define a Table

Create a Table class in your module's `Tables/` directory. Extend Hybridly's `Table` and define columns, query,
refiners, and actions:

```php
namespace Modules\Users\Tables;

use Hybridly\Refining\Filters\DateFilter;
use Hybridly\Refining\Filters\Operator;
use Hybridly\Refining\Filters\SelectFilter;
use Hybridly\Refining\Filters\TernaryFilter;
use Hybridly\Refining\Filters\TextFilter;
use Hybridly\Refining\Filters\TimeSuggestion;
use Hybridly\Refining\Group;
use Hybridly\Refining\Sorts\Sort;
use Hybridly\Tables\Actions\BulkAction;
use Hybridly\Tables\Actions\InlineAction;
use Hybridly\Tables\Columns\TextColumn;
use Hybridly\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Enums\FlashMessage;
use Modules\Datatables\Concerns\HasPerPageLimitation;
use Modules\Datatables\Data\ActionMetaData;
use Modules\Users\Actions\DeleteUserAction;
use Modules\Users\Http\Controllers\BulkDeleteUserController;
use Modules\Users\Models\User;

final class UserTable extends Table
{
    use HasPerPageLimitation;

    protected string $model = User::class;

    protected function defineColumns(): array
    {
        return [
            TextColumn::make('id')->label('#'),
            TextColumn::make('name')->label('Name'),
            TextColumn::make('email')->label('Email'),
        ];
    }

    protected function defineQuery(): Builder
    {
        return User::query()->select(['id', 'name', 'email']);
    }

    protected function defineActions(): array
    {
        return [
            InlineAction::make('edit')
                ->action(fn (User $user) => redirect()->route('users.edit', $user))
                ->metadata(new ActionMetaData(icon: 'i-lucide-square-pen')->toArray()),
            InlineAction::make('delete')
                ->action(fn (User $user) => app(DeleteUserAction::class)->execute($user))
                ->metadata(new ActionMetaData(color: FlashMessage::Error, confirm: true, icon: 'i-lucide-trash-2')->toArray()),
            BulkAction::make('delete')
                ->url(BulkDeleteUserController::class)
                ->metadata(new ActionMetaData(color: FlashMessage::Error, confirm: true, icon: 'i-lucide-trash-2')->toArray()),
        ];
    }

    protected function defineRefiners(): array
    {
        return [
            Sort::make('id'),
            Sort::make('name'),
            TernaryFilter::make('email_verified_at')
                ->label('Verification')
                ->labels('Verified', 'Unverified', 'All'),
            Group::make([
                TextFilter::make(property: 'email', alias: 'search')->defaultOperator(Operator::CONTAINS),
                TextFilter::make(property: 'name', alias: 'search')->defaultOperator(Operator::CONTAINS),
            ])->booleanMode('or'),
        ];
    }
}
```

Pass it to the view from your controller:

```php
public function index(): HybridlyView
{
    return hybridly('users::list-users', ['users' => UserTable::make()]);
}
```

## Relationship Columns

To display data from eager-loaded relationships, use `transformValueUsing` with an underscore-based column name
(avoid dots — TanStack Table interprets them as nested property access):

```php
protected function defineColumns(): array
{
    return [
        TextColumn::make('organization_name')
            ->label('Organization')
            ->transformValueUsing(fn (Project $project) => $project->organization->name),
        TextColumn::make('owner_name')
            ->label('Owner')
            ->transformValueUsing(fn (Project $project) => $project->owner->name),
    ];
}

protected function defineQuery(): Builder
{
    return Project::query()
        ->select(['id', 'name', 'organization_id', 'owner_id'])
        ->with(['organization:id,name', 'owner:id,name']);
}
```

## Inline Actions

Per-row actions are defined in the backend Table class via `defineActions()` using `InlineAction::make()`.
They appear as a dropdown button on each row and in the right-click context menu.

```php
InlineAction::make('edit')
    ->action(fn (User $user) => redirect()->route('users.edit', $user))
    ->metadata(new ActionMetaData(icon: 'i-lucide-square-pen')->toArray()),
```

Use `confirm: true` in `ActionMetaData` to show a confirmation modal before execution:

```php
InlineAction::make('delete')
    ->action(fn (User $user) => app(DeleteUserAction::class)->execute($user))
    ->metadata(new ActionMetaData(color: FlashMessage::Error, confirm: true, icon: 'i-lucide-trash-2')->toArray()),
```

## Bulk Actions

Bulk actions use the `->url()` pattern with invokable controllers. They appear in a bar above the table
when rows are selected. Requires the `selectable` prop.

### Backend: Invokable controller

```php
class BulkDeleteUserController extends Controller
{
    public function __invoke(BulkSelection $bulk): RedirectResponse
    {
        $query = User::query()
            ->when($bulk->hasSelection(), fn ($builder) => $builder->tap(new BulkSelected($bulk)));

        $count = $query->count();
        $query->delete();

        return back()->with(FlashMessage::Success->value, sprintf('%d user(s) successfully deleted', $count));
    }
}
```

### Backend: Table definition

```php
BulkAction::make('delete')
    ->url(BulkDeleteUserController::class)
    ->metadata(new ActionMetaData(color: FlashMessage::Error, confirm: true, icon: 'i-lucide-trash-2')->toArray()),
```

### Routes

```php
Route::post('users/bulk-delete', BulkDeleteUserController::class)->name('users.bulk-delete');
```

## Action Metadata

Actions use `ActionMetaData` DTO (with `#[TypeScript]` for auto-generated TS types) to pass display
configuration to the frontend:

```php
new ActionMetaData(
    color: FlashMessage::Error,  // FlashMessage enum case
    confirm: true,
    confirmMessage: 'This will permanently delete the selected items.',
    icon: 'i-lucide-trash-2',
)
```

| Property         | Type            | Description                              |
|------------------|-----------------|------------------------------------------|
| `icon`           | `?string`       | Lucide icon class (e.g. `i-lucide-trash-2`) |
| `color`          | `?FlashMessage` | Button color via `FlashMessage` enum     |
| `confirm`        | `bool`          | Show confirmation modal before execution |
| `confirmMessage` | `?string`       | Custom confirmation message              |

## Multi-Select Filters

`SelectFilter` supports multi-select via the `->multiple()` method. The frontend dropdown automatically
shows checkmarks and allows toggling multiple options. Hybridly handles `whereIn` queries automatically
for standard column filters.

```php
SelectFilter::make('status')
    ->label('Status')
    ->multiple()
    ->options(ProjectStatus::labelMap()),
```

For custom query callbacks with `->multiple()`, the callback receives `array $selectedOptions`
(keyed by value). Use `array_keys()` with `whereIn`:

```php
SelectFilter::make('member_role')
    ->label('Member Role')
    ->multiple()
    ->options($roleOptions)
    ->query(fn (Builder $builder, array $selectedOptions) => $builder->whereHas(
        'users',
        fn (Builder $query) => $query->whereIn('pivot.role', array_keys($selectedOptions)),
    )),
```

## Frontend: Use the Datatable Component

The view receives a typed `Table<T>` prop and passes it to the `Datatable` component.
Actions and filters are read from the backend — no frontend action definitions needed:

```vue
<script setup lang="ts">
type UserData = Modules.Users.Data.UserData;

defineProps<{ users: Table<UserData> }>();
</script>

<template layout="core::main">
  <Datatable selectable :table="users" />
</template>
```

## Custom Cell Rendering

Override how a column renders using the `#<column-name>-cell` slot pattern.
The slot name is derived from the column key passed to `TextColumn::make('<column-name>')`.

The slot receives `slotProps` which may be `undefined`, so always use optional chaining.
Access typed row data via `slotProps.row.original` (the DTO object):

```vue
<Datatable :table="users">
  <template #name-cell="slotProps">
    <div v-if="slotProps?.row" class="flex items-center gap-3">
      <UAvatar :alt="slotProps.row.original.name" />
      <span v-text="slotProps.row.original.name" />
    </div>
  </template>
</Datatable>
```

## Dynamic Badge Rendering

For status/priority columns, use Nuxt UI's `UBadge` with backend-driven colors.
This avoids hardcoding color maps in the frontend — colors are defined once in PHP enums
and passed as view props.

### Step 1: Add color methods to the PHP enum

```php
#[TypeScript]
enum ProjectStatus: string
{
    case Active = 'active';
    case Archived = 'archived';
    case OnHold = 'on_hold';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Archived => 'Archived',
            self::OnHold => 'On Hold',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Active => 'success',
            self::Archived => 'neutral',
            self::OnHold => 'warning',
        };
    }

    /** @return array<string, string> */
    public static function colorMap(): array
    {
        return collect(self::cases())->mapWithKeys(
            fn (self $case) => [$case->value => $case->color()],
        )->all();
    }

    /** @return array<string, string> */
    public static function labelMap(): array
    {
        return collect(self::cases())->mapWithKeys(
            fn (self $case) => [$case->value => $case->label()],
        )->all();
    }
}
```

Available Nuxt UI badge colors: `primary`, `secondary`, `success`, `info`, `warning`, `error`, `neutral`.

### Step 2: Pass color and label maps from the controller

```php
public function index(): HybridlyView
{
    return hybridly('projects::list-projects', [
        'projects' => ProjectTable::make(),
        'statusColors' => ProjectStatus::colorMap(),
        'statusLabels' => ProjectStatus::labelMap(),
    ]);
}
```

### Step 3: Render badges in the view using cell slots

The `#<column-name>-cell` slot receives the row data. Use the color/label maps dynamically:

```vue
<script setup lang="ts">
const props = defineProps<{
  projects: Table<Modules.Projects.Data.ProjectData>;
  statusColors: Record<string, string>;
  statusLabels: Record<string, string>;
}>();
</script>

<template layout="core::main">
  <datatable selectable :table="props.projects">
    <template #status-cell="slotProps">
      <UBadge
        v-if="slotProps?.row"
        :color="props.statusColors[slotProps.row.original.status] ?? 'neutral'"
        :label="props.statusLabels[slotProps.row.original.status] ?? slotProps.row.original.status"
        variant="subtle"
      />
    </template>
  </datatable>
</template>
```

Multiple badge columns work the same way — just add more `#<column>-cell` slots
(e.g., `#status-cell` and `#priority-cell` in the tasks listing).

For boolean columns (like `is_active`), badges can be rendered inline without backend maps:

```vue
<Datatable :table="organizations">
  <template #active-cell="slotProps">
    <UBadge
      v-if="slotProps?.row"
      :color="slotProps.row.original.active ? 'success' : 'error'"
      :label="slotProps.row.original.active ? 'Active' : 'Inactive'"
      variant="subtle"
    />
  </template>
</Datatable>
```

> **Note:** If the column name contains underscores (e.g., `is_active`), the slot name
> follows the same pattern: `#is_active-cell`.

## Create Button

Use the `#create` slot to add a button in the table header:

```vue
<Datatable :table="users">
  <template #create>
    <UButton icon="i-lucide-plus" @click="router.get(route('users.create'))" />
  </template>
</Datatable>
```

## Props

| Prop              | Type                    | Default             | Description                           |
|-------------------|-------------------------|---------------------|---------------------------------------|
| `table`           | `Table<T>`              | **required**        | Hybridly table object from controller |
| `selectable`      | `boolean`               | `false`             | Enable row selection checkboxes       |
| `searchable`      | `boolean`               | `true`              | Show search input                     |
| `columns`         | `ColumnDef<T>[]`        | auto-generated      | Override auto-generated columns       |
| `hiddenColumns`   | `readonly string[]`     | `[]`                | Columns hidden by default             |
| `stickyHeader`    | `boolean`               | `false`             | Sticky table header on scroll         |
| `emptyIcon`       | `string`                | `i-lucide-database` | Icon shown when table is empty        |
| `emptyText`       | `string`                | `No data available` | Text shown when table is empty        |
| `loadingAnimation`| `string`                | `carousel`          | Loading animation style               |
