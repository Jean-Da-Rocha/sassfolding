# 06 - Datatables

Usage documentation: `docs/DATATABLE.md`. This file covers the internals.

## Shape

A reusable table system wrapping Hybridly Tables on the server and Nuxt UI's `UTable`, itself
TanStack Table, on the client. It depends only on Core.

The PHP side is two files: `HasPerPageLimitation`, a trait reading `per_page` with a default of
ten, and `ActionMetaData`, a `#[TypeScript]` DTO carrying an action's presentation (colour,
confirmation, message, icon) to the front-end.

A table class declares `$model`, `defineColumns()`, `defineQuery()`, `defineActions()` and
`defineRefiners()`. Because `Model::shouldBeStrict()` is on, `defineQuery()` must select its
columns explicitly and eager load what the columns touch.

## The Vue side

`datatable.vue` is a generic component exposing dynamic slots per column. Eight composables split
the work:

| Composable | Role |
|------------|------|
| `useTableColumns` | Builds the TanStack column definitions, selection column, action column |
| `useTableFilters` | Labels, icons, dropdown entries, active count, clearing |
| `useTableSelection` | Selection, shift-click ranges, counts |
| `useTableActions` | Inline and bulk actions, context menu, confirmation routing |
| `useTableColumnVisibility` | Showing and hiding columns |
| `useTablePagination` | Paginator metadata, navigation, page size |
| `useTableConfirmation` | Confirmation modal and pending action |
| `useTableSearch` | Search bound to the `search` filter, debounced, resets to page one |

They all take a `Datatable<T>`, which is `UseTableReturn<Table<T>>`. This used to be `any`,
because `ReturnType<typeof useTable>` did not resolve across generic boundaries. Hybridly now
exports the named interface, so the module is fully typed and the local duplicates of upstream
types are gone: `TablePaginatorMeta` is `Paginator['meta']`, action types derive from
`UseTableBulkActionItem` and friends, and `TableActionMetadata` is the generated
`Modules.Datatables.Data.ActionMetaData`.

Two behaviours use Hybridly rather than hand-rolled logic: shift-click ranges come from
`getBulkSelectionRange`, and the active date suggestion comes from the `is_current` flag Hybridly
serialises, rather than comparing strings.

## Tests

`modules/Datatables/Tests/Unit/*.test.ts`, run by Vitest through `make vitest`. They cover the
wiring that belongs to this module and deliberately do not re-test Hybridly: range ordering,
filter query parsing and action serialisation are upstream concerns with their own suite.

The component itself is not covered. Changes to `datatable.vue` need a manual pass in the browser.

## Deliberately not implemented

Full date picker, numeric filter, row expansion, CSV export, grouping, drag and drop, tree data,
column resizing. Do not build any of them without being asked, and check for a native Hybridly or
Nuxt UI answer before writing anything custom.
