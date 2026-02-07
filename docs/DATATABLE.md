# Datatables

The Datatables module provides a reusable, full-featured table system built on top of
[Hybridly Tables](https://hybridly.dev/guide/tables.html) (server-side) and
[Nuxt UI's UTable](https://ui.nuxt.com/components/table) (TanStack Table, client-side).

## Features

- Server-side pagination, sorting, and search
- Column visibility toggle
- Row selection with bulk actions
- Per-row inline actions (with optional confirmation modal)
- Right-click context menu
- Custom cell rendering via slots
- Loading state and empty state

## Backend: Define a Table

Create a Table class in your module's `Tables/` directory. Extend Hybridly's `Table` and define columns, query,
and refiners (sorts + filters):

```php
namespace Modules\Users\Tables;

use Hybridly\Refining\Filters\Filter;
use Hybridly\Refining\Group;
use Hybridly\Refining\Sorts\Sort;
use Hybridly\Tables\Columns\TextColumn;
use Hybridly\Tables\Table;
use Modules\Datatables\Concerns\HasPerPageLimitation;
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

    protected function defineRefiners(): array
    {
        return [
            // Sortable columns
            Sort::make('id'),
            Sort::make('name'),

            // Search filter (matches across multiple columns)
            Group::make([
                Filter::make(property: 'email', alias: 'search')->loose(),
                Filter::make(property: 'name', alias: 'search')->loose(),
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

## Frontend: Use the Datatable Component

The view receives a typed `Table<T>` prop and passes it to the `Datatable` component:

```vue
<script setup lang="ts">
type UserData = Modules.Users.Data.UserData;

defineProps<{ users: Table<UserData> }>();
</script>

<template layout="core::main">
  <Datatable resource-name="user" :table="users" />
</template>
```

## Inline Actions

Per-row actions appear as a dropdown button on each row and in the right-click context menu.

**Route-based** actions navigate to a named route, automatically passing the row's `id`. Use `confirm: true` for a
confirmation modal and `method: 'delete'` for destructive actions:

```ts
const inlineActions = [
  {
    icon: 'i-lucide-square-pen',
    label: 'Edit',
    route: 'users.edit'
  },
  {
    color: 'error',
    confirm: true,
    icon: 'i-lucide-trash-2',
    label: 'Delete',
    method: 'delete',
    route: 'users.destroy'
  },
] satisfies InlineAction<UserData>[];
```

**Custom handler** actions run arbitrary logic via `onSelect(record)`:

```ts
const inlineActions = [
  {
    icon: 'i-lucide-copy',
    label: 'Duplicate',
    onSelect: user => duplicateUser(user)
  },
] satisfies InlineAction<UserData>[];
```

## Bulk Actions

Bulk actions appear in a bar above the table when rows are selected. Requires the `selectable` prop:

```ts
const bulkActions = [
  {
    color: 'error',
    icon: 'i-lucide-trash-2',
    label: 'Delete Selected',
    onSelect: (rows) => {}
  },
] satisfies BulkAction<UserData>[];
```

```vue
<Datatable :bulk-actions="bulkActions" selectable :table="users" />
```

## Custom Cell Rendering

Override how a column renders using the `#<column-name>-cell` slot pattern:

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
| `inlineActions`   | `InlineAction<T>[]`     | —                   | Per-row action definitions            |
| `bulkActions`     | `BulkAction<T>[]`       | —                   | Multi-row action definitions          |
| `resourceName`    | `string`                | —                   | Used for route resolution in actions  |
| `selectable`      | `boolean`               | `false`             | Enable row selection checkboxes       |
| `searchable`      | `boolean`               | `true`              | Show search input                     |
| `columns`         | `ColumnDef<T>[]`        | auto-generated      | Override auto-generated columns       |
| `hiddenColumns`   | `string[]`              | `[]`                | Columns hidden by default             |
| `stickyHeader`    | `boolean`               | `false`             | Sticky table header on scroll         |
| `emptyIcon`       | `string`                | `i-lucide-database` | Icon shown when table is empty        |
| `emptyText`       | `string`                | `No data available` | Text shown when table is empty        |
| `loadingAnimation`| `string`                | `carousel`          | Loading animation style               |
