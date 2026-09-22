# 05 - Frontend conventions

## Auto-imports, the thing to know first

Configured in `vite.config.ts` through `unplugin-auto-import`, carried by Nuxt UI.
**Never write a manual import for any of these, ESLint rejects it.**

- **Vue**: `ref`, `computed`, `watch`, `reactive`, `onMounted`, `onUnmounted`, `h`, and the rest
- **VueUse**: everything from `@vueuse/core`
- **Hybridly values**: `router`, `route`, `can`, `getRouterContext`, `useForm`, `useDialog`,
  `useTable`, `useProperty`, `useProperties`, `useRefinements`, `usePaginator`, `registerHook`,
  `setProperty`, `useRoute`, `useQueryParameter`, `useBulkSelect`, `getBulkSelectionRange`
- **Hybridly types**: `Table`, `UseTableReturn`, `UseTableColumn`, `UseTableRecordItem`,
  `UseTableBulkActionItem`, `UseTableInlineActionItem`, `Column`, `RecordIdentifier`,
  `BoundFilterRefinement`, `FilterRefinement`, `SortDirection`, `InlineAction`, `BulkAction`,
  `TimeSuggestion`, `TimeframeSuggestion`
- **Unhead**: `useHead`, `useSeoMeta`
- **@internationalized/date**: `parseDate` and the `DateValue` type
- **Nuxt UI types**: `ColumnDef`, `DropdownMenuItem`, `NavigationMenuItem`, `ToastProps`
- **Every export under `modules/**`**: composables, types, constants
- **Every `.vue` file under `modules/**/Components/`**, plus Lucide icons with no prefix

Generated declarations land in `.hybridly/auto-imports.d.ts` and `.hybridly/components.d.ts`,
neither tracked by git. When in doubt about whether something is auto-imported, read those files.

This also applies inside Vitest, which reuses the build config.

## TypeScript

`type` rather than `interface`, `readonly` properties, `as const satisfies Record<K, V>` for
config maps, `export function` for exported functions, explicit return types on composables.

Known traps:

- `Datatable<T>` is the alias to use for a table object. `ReturnType<typeof useTable>` produces
  conditional types that do not resolve across generic component boundaries.
- `useHybridlyLoading` must not carry an explicit `Ref<boolean>` return type, it breaks Volar's
  ref unwrapping in templates.
- Generic components combined with `withDefaults` can lose type resolution, keep `?? []` fallbacks.

## Hybridly patterns

- `form.submit` takes options, so it can never be passed as an event handler. Always call it:
  `@submit.prevent="form.submit()"`.
- `close` from `useDialog` takes options too: `@click="close()"`.
- `useForm` resets on success by default (`resetOnSuccess`), there is no `reset` option any more.

## Layouts and navigation

`main.layout.vue` is the Nuxt UI dashboard, `guest.layout.vue` centres a card for the auth
screens. Both live in Core and are referenced as `core::main` and `core::guest`.

Each module exposes a `use{Module}Navigation()` composable returning `ModuleNavigationItem[]`,
which extends Nuxt UI's `NavigationMenuItem` with `group` (`main`, `footer` or `hidden`), `order`
(a global sort key) and `routeName` (matched against `useRoute().current` for the active state and
the navbar title). `useNavigation()` in Core aggregates them.

Adding a module means creating its composable and adding it to that list.

Core's own navigation exposes Home, Inbox and Settings, which point at nothing. They are
placeholders.

## Core composables

| Composable | Role |
|------------|------|
| `useAuth()` | `user` and `isAuthenticated` from the shared property |
| `useFlashToast()` | Watches `flash` and raises Nuxt UI toasts, with an icon and a duration per severity |
| `useDynamicFavicon()` | Builds an SVG favicon from the app initial and the primary colour, follows the theme |
| `useHybridlyLoading()` | Boolean ref wired to the router's `start` and `after` hooks |
| `useNavigation()` | Navigation aggregation |
| `useDateField(field)` | Bridges a form string and a `DateValue` |

## Documentation

Hybridly: `https://hybridly.dev/guide/`, and every page has a Markdown version at the same URL
with a `.md` suffix, which is much easier to read than the HTML.
Nuxt UI: `https://ui.nuxt.com/llms.txt`.
