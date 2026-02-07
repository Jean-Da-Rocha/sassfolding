# Claude Instructions

## Project

This is a scaffolding base project that will be used as a foundation to quickly scaffold SaaS projects.
It uses a custom modular monolith approach where each module corresponds to a 'domain' of the application.
The front-end components are also in the same module folder to improve readability / maintenance.

## Stack

- PHP 8.5
- Laravel 12
- MySQL
- Redis (caching, sessions, queue driver)
- Hybridly
- Vue 3.5 (composition API, TS)
- Nuxt UI v4

## Critical Rules (non-negotiable)

- DO NOT change database schema or migrations unless explicitly asked.
- DO NOT refactor global architecture.
- DO NOT modify authentication or authorization without approval.
- NO breaking changes.
- NO dependency changes unless requested.

## Module Architecture

| Type           | Module         | Can Import From                          | Importable By        |
|----------------|----------------|------------------------------------------|----------------------|
| Foundation     | Core           | Laravel/Vendor, Users\Data               | All modules          |
| Infrastructure | Datatables     | Core                                     | All modules          |
| Domain         | Users          | Core, Datatables                         | All (Model/Data only)|
| Feature        | Authentication | Core, Datatables, Users (Model/Data)     | None                 |

- Users\Actions are **private** to the Users module (not importable by others)
- Feature modules **cannot** import from each other
- Architecture tests in `modules/Core/Tests/Architecture/ModuleBoundariesTest.php` enforce these rules at CI time

## Module Directory Structure

Not all modules have all directories. This is the canonical layout:

```
modules/{ModuleName}/
  Actions/              Business logic (action classes)
  Concerns/             Shared PHP traits
  Data/                 Spatie LaravelData DTOs
  Database/             Factories, Migrations, Seeders
  Enums/                PHP backed enums (#[TypeScript] for TS generation)
  Http/Controllers/     HTTP controllers (slim, delegate to Actions)
  Http/Middleware/       Module-specific middleware
  Http/Responses/       Response classes
  Models/               Eloquent models
  Providers/            Service providers (routes, migrations, views)
  Resources/
    Components/         Vue components (auto-registered)
    Composables/        Vue composables (auto-imported)
    Layouts/            Layout files [Core only]
    Types/              TypeScript type files (auto-imported)
    Views/              Hybridly view pages
  Routes/               Route files (web.php)
  Tables/               Hybridly Table classes
  Tests/                Pest tests (Feature/, Unit/, Architecture/)
```

## Naming Conventions

| Artifact   | Pattern                    | Example                          |
|------------|----------------------------|----------------------------------|
| View       | `kebab-case.view.vue`      | `list-users.view.vue`            |
| Component  | `kebab-case.vue`           | `profile-info.vue`               |
| Layout     | `name.layout.vue`          | `main.layout.vue`                |
| Composable | `useCamelCase.ts`          | `useFlashToast.ts`               |
| Type file  | `camelCase.ts`             | `pagination.ts`                  |
| DTO        | `{Name}Data.php`           | `UserData.php`                   |
| Table      | `{Name}Table.php`          | `UserTable.php`                  |
| Action     | `{Verb}{Noun}Action.php`   | `CreateNewUserAction.php`        |
| Controller | `{Name}Controller.php`     | `UserController.php`             |
| Enum       | `{Name}.php`               | `FlashMessage.php`               |

## Auto-Import System

The following are **globally auto-imported** via `unplugin-auto-import` in `vite.config.ts`. **Do NOT add manual imports for these — unnecessary imports cause ESLint errors.**

- **Vue**: `ref`, `computed`, `watch`, `onMounted`, `onUnmounted`, `h`, `resolveComponent`, `nextTick`, `Component` type, etc.
- **Hybridly**: `router`, `route`, `can`, `useForm`, `useDialog`, `useTable`, `useProperty`, `useProperties`, `registerHook`, `usePaginator`, `useRefinements`, `useRoute`, `setProperty` + types `RouteName`, `NavigationResponse`
- **VueUse**: all `@vueuse/core` exports
- **Nuxt UI types**: `ColumnDef`, `DropdownMenuItem`, `NavigationMenuItem`, `ToastProps`
- **Unhead**: `useHead`, `useSeoMeta`
- **All module exports**: every export from `modules/**` (composables, types, constants) is globally available
- **Components**: all `.vue` files in `modules/**/Components/` are auto-registered (no import needed)

## Coding Guidelines

- Avoid unnecessary comments.
- Do not comment on obvious code.
- Comment only non-trivial business rules or technical constraints.
- No business logic in controllers.
- Explicit exceptions only.
- Follow existing conventions, even if imperfect.
- Readability and safety over cleverness.
- Use Hybridly documentation https://hybridly.dev/guide/ for both back and front.

## Laravel Instructions

- Using Services in Controllers: if Service class is used only in ONE method of Controller, inject it directly into that
  method with type-hinting. If Service class is used in MULTIPLE methods of Controller, initialize it in Constructor.
- **Eloquent Observers** should be registered in Eloquent Models with PHP Attributes, and not in AppServiceProvider.
  Example: `#[ObservedBy([UserObserver::class])]` with `use Illuminate\Database\Eloquent\Attributes\ObservedBy;` on top
- Aim for "slim" Controllers and put larger logic pieces in Service classes
- Use Laravel helpers instead of `use` section classes. Examples: use `auth()->id()` instead of `Auth::id()` and adding
  `Auth` in the `use` section. Other examples: use `redirect()->route()` instead of `Redirect::route()`, or
  `str()->slug()` instead of `Str::slug()`.
- Don't use `whereKey()` or `whereKeyNot()`, use specific fields like `id`. Example: instead of
  `->whereKeyNot($currentUser->getKey())`, use `->where('id', '!=', $currentUser->id)`.
- Don't add `::query()` when running Eloquent `create()` statements. Use `User::create()` directly.
- When adding columns in a migration, update the model's `$fillable` array to include those new attributes.
- Never chain multiple migration-creating commands with `&&` or `;` — they may get identical timestamps.
- Enums: always use enum cases (or `->value`) instead of raw strings everywhere.
- Controllers: Single-method Controllers should use `__invoke()`; multi-method RESTful controllers should use
  `Route::resource()->only([])`
- Follow the custom modular monolith architecture even if it doesn't fully respect Laravel's default architecture.
- **DTOs**: use Spatie LaravelData. Classes are `final`, properties are `readonly`, use `#[MapName(SnakeCaseMapper::class)]`.
- **Flash messages**: always use `FlashMessage` enum (`FlashMessage::Success->value`), never raw strings.

## Front-end Instructions

- Use Nuxt UI documentation from https://ui.nuxt.com/llms.txt
- Favor a slick UI, with best practices and conventions
- The front-end code must be well typed using TS and follow Vue 3 best practices and the ESLint configuration

### TypeScript Conventions

- Use `type` not `interface` (ESLint: `ts/consistent-type-definitions`)
- Mark type properties as `readonly`
- Use `as const satisfies Record<K, V>` for config maps (preserves literal types + enforces shape)
- Use `satisfies Type[]` for typed arrays/objects without widening
- Use `export function` for exported functions (ESLint enforced), `const fn = () => {}` for inner functions
- Explicit return types on composables, EXCEPT when the type causes Volar issues (see gotchas below)

### Hybridly Patterns

- **Dialog views**: controller uses `->base('route.name')`, frontend wraps content in `<HybridlyModal>`
- **Forms**: use `useForm<T>({ fields, method, url, hooks })` from Hybridly
- **Tables**: controller passes `Table<T>` via `hybridly('view', ['table' => TableClass::make()])`

### Known Gotchas

- `TablePaginatorMeta`: use this explicit type instead of extracting from `ReturnType<typeof useTable>` (conditional types are unresolvable)
- `useHybridlyLoading`: do NOT add explicit `Ref<boolean>` return type — it breaks Volar template ref unwrapping
- Generic components (`generic="T"`) + `withDefaults` can lose type resolution — use `?? []` fallbacks

## Datatable Usage

See `docs/DATATABLE.md` for full datatable documentation (basic usage, inline/bulk actions, custom cell rendering, props reference).

## Testing Instructions

### Before Writing Tests

1. **Check database schema** - Use `database-schema` tool to understand:
    - Which columns have defaults
    - Which columns are nullable
    - Foreign key relationship names

2. **Verify relationship names** - Read the model file to confirm:
    - Exact relationship method names (not assumed from column names)
    - Return types and related models

3. **Test realistic states** - Don't assume:
    - Empty model = all nulls (check for defaults)
    - `user_id` foreign key = `user()` relationship (could be `author()`, `employer()`, etc.)

### Hybridly-Specific Testing

- **Dialog views** (routes with `->base()`): `assertHybridView()` returns the base view, use `assertHybridDialog(view:, properties:)` for the dialog component
- **Redirect status codes**: non-Hybridly test requests get 302 (`HTTP_FOUND`), not 303. The 303 conversion only applies to Hybridly XHR requests for PUT/PATCH/DELETE methods.

## Git Workflow

- **Conventional Commits** enforced by `commitlint` (`@commitlint/config-conventional`)
- **Branch naming**: `{type}/{TICKET-ID}/description` — types: `build`, `bugfix`, `bump`, `docs`, `experimental`, `feature`, `hotfix`, `merge`, `release`, `test`
- **Pre-commit hooks** (via Husky + lint-staged):
  - `*.vue, *.ts` files: ESLint auto-fix
  - `*.php` files: Laravel Pint
- **Pre-push**: branch name validation

## Workflow

- Always explain the approach before coding.
- The application is run via Docker; do not assume local system configuration.
- For Docker architecture details (environments, CI, production), see `.claude/DOCKER_ARCHITECTURE.md`.
- List risks and trade-offs.
- Wait for validation when unsure.
- Add or update tests when logic changes.

## Key Commands

All commands run inside Docker via `make` targets. Never run PHP/Node commands directly.

- `make help` — list all available commands (grouped by category)
- `make test` — run Pest tests (`make test filter=UserControllerTest` to filter)
- `make migrate` — run database migrations
- `make fresh` — drop all tables and re-run migrations (`make fresh seed=1` to also seed)
- `make seed module=Users class=UserSeeder` — run a module-scoped seeder
- `make eslint` — run ESLint with auto-fix
- `make vue-tsc` — run TypeScript type checking
- `make phpstan` — run PHPStan static analysis
- `make pint` — run PHP code style fixer
- `make cache-clear` — clear all Laravel caches
- `make logs` — tail all container logs (`make logs svc=hybridly` for one service)
- `make shell` — open bash in the hybridly container
- `make ps` — show status of all containers
- `make artisan cmd="..."` — run any Artisan command
- `make pnpm cmd="..."` — run any pnpm command
- `make composer cmd="..."` — run any Composer command

## Allowed Usage

- Code understanding and explanation
- Local refactors (single class / service)
- Test generation (Pest)
- Performance analysis (read-only)

## Forbidden Usage

- Large-scale refactors
- Schema or data migrations
- Speculative "improvements"
- Silent behavior changes

## Goal

Assist without destabilizing the system.
