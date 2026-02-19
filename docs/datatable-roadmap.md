# Datatable Feature Roadmap

## Already Implemented
- Search (global filter via `TextFilter` group with `Operator::CONTAINS`)
- Server-side pagination (with state preservation)
- Column visibility toggle
- Row selection (bridged between Hybridly bulk select and UTable)
- Shift-click bulk selection (range select between last clicked and current)
- Inline actions (backend-defined via `InlineAction::make()`, with confirmation modal)
- Bulk actions (backend-defined via `BulkAction::make()` with `->url()` pattern, invokable controllers using `BulkSelection` + `BulkSelected`)
- Context menu (right-click row actions)
- Sorting (per-column, server-side via Hybridly refinements)
- Loading state animation
- Sticky header
- Empty state (customizable icon + text)
- Custom cell rendering (named slots)
- Action metadata via `ActionMetaData` DTO (`#[TypeScript]`, uses `FlashMessage` enum for color)

### Filter System
- **Generic filter toolbar** — Filters render as dropdown buttons in the header, active filters highlighted
- **TernaryFilter** — 3-state dropdown (All / Yes / No), used for nullable columns (e.g., `email_verified_at`)
- **DateFilter** — Dropdown with server-defined suggestion chips (e.g., Today, Last 7 days), uses `AFTER` operator
- **SelectFilter** — Dropdown with options from enum, array, or DB query. Active state shows check icon, Clear button when active
- **BooleanFilter** — 3-item dropdown identical to ternary (All / Yes / No)
- **TrashedFilter** — 3-item dropdown (Without trashed / With trashed / Only trashed) for soft-delete models
- **Clear all filters** button when any filter is active
- **Extensible architecture** — `useTableFilters` composable + `getFilterItems()` dispatch by `filter.type`

### Composable Inventory
| Composable | Responsibility |
|---|---|
| `useTableSearch` | Binds search filter with 300ms debounce, resets page on search |
| `useTablePagination` | Page navigation + per-page size dropdown |
| `useTableFilters` | Filter toolbar: items, labels, icons, clear. Supports 5 filter types |
| `useTableColumnVisibility` | Column show/hide toggle dropdown |
| `useTableConfirmation` | Confirmation modal state for destructive actions |
| `useTableActions` | Inline + bulk action execution, context menu, typed `bulkActions` computed |
| `useTableSelection` | Row selection state, shift-click range selection, deselect all |
| `useTableColumns` | TanStack `ColumnDef<T>[]` generation (selection, data, actions columns) |

### Hybridly Filter Types — Implementation Status

| Filter Type | Backend | Frontend UI | Notes |
|---|---|---|---|
| **TernaryFilter** | Done | Done (dropdown) | 3-state: All / True label / False label |
| **DateFilter** | Done | Done (dropdown + suggestions) | Quick-select chips, `AFTER` operator |
| **SelectFilter** | Done | Done (dropdown) | Options from enum, array, or DB. Check icon on active |
| **BooleanFilter** | Available | Done (dropdown) | Same as ternary UI |
| **TrashedFilter** | Available | Done (dropdown) | Without / With / Only trashed |
| **NumericFilter** | Available | Planned | Backend class exists in Hybridly, frontend TBD |
| **CallbackFilter** | Available | — | Custom closure, needs per-case UI |

## High Priority
- **NumericFilter frontend** — Preset-based dropdown items (like date suggestions), needs `getNumericFilterItems()` in `useTableFilters.ts`
- **Row expansion** — Show row details inline without navigating. Common for order details, activity logs, nested data.
- **Export (CSV/Excel)** — SaaS users almost always need data export.

## Medium Priority
- **DateFilter calendar** — Full date picker (requires `@internationalized/date` dependency for `UCalendar`)
- **Column pinning** — Pin ID/name/actions columns on wide tables so they stay visible on horizontal scroll.
- **Virtualization** — Prevents DOM bloat for large visible row counts. Less urgent if always paginating with reasonable page sizes.

## Lower Priority (Nice to Have)
- **Row grouping** — Reporting-style views (group invoices by client, etc.)
- **Drag and drop** — Row reordering for menu builders, priority lists.
- **Tree data** — Hierarchical display (org charts, nested categories).
- **Column resizing** — Users adjusting column widths.

## Upcoming: Domain Expansion Plan
Full plan saved at `.claude/plans/swirling-bouncing-kurzweil.md`. Summary:
- **Organizations module** — `organizations` + `organization_user` pivot table, TernaryFilter, TrashedFilter, SelectFilter (role), DateFilter
- **Projects module** — `projects` + `tasks` tables, SelectFilter (enum + relationship), TernaryFilter, BooleanFilter (is_pinned), NumericFilter (estimated_hours), DateFilter
- Both modules get full Table classes, controllers, views, and tests
