# 03 - Module architecture

A hand-rolled modular monolith. One module per domain. The front-end of a module lives in the same
folder as its back-end, deliberately, for readability.

## Modules and boundaries

| Type | Module | May import | Importable by |
|------|--------|------------|---------------|
| Foundation | **Core** | Laravel and vendor, `Users\Data` | Everything |
| Infrastructure | **Datatables** | Core | Everything |
| Domain | **Users** | Core, Datatables | Everything, Model and Data only |
| Feature | **Authentication** | Core, Datatables, Users (Model and Data) | Nothing |

`Users\Actions` is private to its module. Feature modules may not import from each other.

`modules/Core/Tests/Architecture/ModuleBoundariesTest.php` enforces this with Pest arch tests,
plus structural rules: controllers extend the base controller, Data extend Spatie's `Data`, enums
are backed, models extend Eloquent's `Model`, providers extend `ServiceProvider`, and no
`dd`/`dump`/`ray`/`var_dump`/`print_r` survives.

When a second feature module appears, add it to the Core and Datatables rules so it cannot be
imported by them or by another feature module.

## Layout

```
modules/{Module}/
  Actions/              Business logic, one class per action
  Architecture/         [Core only] Hybridly component loader
  Concerns/             Shared traits
  Data/                 Spatie LaravelData DTOs
  Database/             Factories, Migrations, Seeders
  Enums/                Backed enums, #[TypeScript] when exposed
  Http/Controllers/     Thin controllers
  Http/Middleware/
  Http/Responses/
  Models/
  Providers/            {Module}ServiceProvider plus RouteServiceProvider
  Resources/
    Application/        [Core only] main.ts, root.blade.php, tailwind.css
    Components/         Vue components, auto-registered
    Composables/        Auto-imported
    Layouts/            [Core only]
    Types/              Auto-imported TypeScript types
    Views/              Hybridly views
  Routes/web.php
  Tables/               Hybridly Table classes
  Tests/                Pest tests and Vitest tests
```

Not every module has every directory. Authentication only has a provider and views. Datatables has
no service provider at all: it registers nothing, so it does not need one.

## Registration

`bootstrap/providers.php` lists the providers alphabetically. A module provider loads its
migrations and registers its `RouteServiceProvider`, which loads `Routes/web.php` into the `web`
middleware group.

**Views and layouts are no longer registered by the providers.** Hybridly removed
`loadViewsFrom` and `loadLayoutsFrom` in the 0.10 beta line. Registration now goes through a
single `ComponentLoader`, configured in `config/hybridly.php`:

```php
'architecture' => [
    'component_loader' => ModuleComponentLoader::class,
    ...
],
```

`Modules\Core\Architecture\ModuleComponentLoader` walks `modules/*/Resources/Views` and
`modules/*/Resources/Layouts` and produces flat `{module}::{name}` identifiers.

Hybridly ships a `ModulesComponentLoader`, but it builds the identifier from the whole path below
the module, which would turn `users::list-users` into `users::resources.views.list-users`. The
custom loader exists solely to keep identifiers flat, and nothing else.

## Adding a module

1. Create the directories
2. Create `{Name}ServiceProvider` and `RouteServiceProvider`
3. Register the provider in `bootstrap/providers.php`, alphabetically
4. Add the boundary rules in `ModuleBoundariesTest.php`
5. Create `Resources/Composables/use{Name}Navigation.ts` and add it to `useNavigation()` in Core
6. Run `make test filter=Architecture`

Views and layouts are picked up automatically, nothing to register.
