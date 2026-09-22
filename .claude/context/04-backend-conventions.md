# 04 - Backend conventions

Every PHP file starts with `declare(strict_types=1);`.

## Hybridly API

This matters most, because the 0.10 beta line changed it and older examples are misleading.

| Purpose | Call |
|---------|------|
| Render a view | `hybridly()->view('module::view', [...])` |
| Dialog over a base page | `->configureDialog(baseUrl: route('users.index'))` |
| Return type | `Hybridly\HybridResponseFactory` |
| Share global properties | `hybridly()->share($arrayable)` |

`hybridly()` takes no argument: it returns the Hybridly instance. `->base('route.name')` is gone.

Global properties are shared from a dedicated middleware,
`Modules\Core\Http\Middleware\ShareGlobalProperties`, registered in `bootstrap/app.php` alongside
Hybridly's own `HandleHybridRequests`, which handles the protocol. The two are separate classes
with separate jobs. `share()` accepts an `Arrayable`, so the whole `SharedData` DTO goes in one
call.

Validation errors live in the payload, not in a shared property.

## Controllers

Thin, no business logic. Single-method controllers use `__invoke()`, RESTful ones use
`Route::resource()->except(...)`. Inject an Action in the method when it is used once, in the
constructor when used several times. Return `back()->with(FlashMessage::Success->value, '...')`.

Inject the DTO directly to get validation before the method body runs.

## DTOs

`final`, `readonly` properties, `#[MapName(SnakeCaseMapper::class)]`, camelCase in PHP converted
to snake_case for Eloquent and the front-end.

- **One Data class per entity** for output, create and update. Use `Optional` for fields that are
  not always present.
- **Validation through PHP attributes** (`#[Max(255)]`, `#[Email]`, `#[Exists(...)]`), never a
  `rules()` method. For a unique rule that must ignore the current record, use
  `RouteParameterReference`.
- Enum-typed properties rather than `string`.
- `Carbon\Carbon`, not `CarbonInterface`.
- Property order follows the database column order.

`UserData` is the reference example.

## Actions

`final` classes with a single public method, usually `execute()`. They carry the business logic
and flash their own message. Fortify actions implement Fortify's contracts and are wired in
`UserServiceProvider`.

## Models

Annotate relations fully for PHPStan level 9. Declare observers with
`#[ObservedBy([...])]` on the model, not in a provider.

`AppServiceProvider` sets globally:

```php
URL::forceScheme('https');
Model::shouldBeStrict();
Model::unguard();
Date::use(CarbonImmutable::class);
Validator::excludeUnvalidatedArrayKeys();
```

`shouldBeStrict()` makes any access to an unloaded attribute or any lazy load throw, which is why
table queries select explicitly. `unguard()` means `$fillable` documents rather than protects.
Dates are immutable.

## Type generation

`spatie/laravel-typescript-transformer` collects Data classes and `#[TypeScript]` enums. Hybridly
then writes them to `.hybridly/php-types.d.ts` through `php artisan hybridly:types`, overriding
the package's own output path. Namespaces follow the PHP namespace:
`Modules.Users.Data.UserData`.

## Forbidden

No business logic in controllers, no `whereKey()`, no `::query()` before `create()`, no facade
when a helper exists (`auth()->id()`, `redirect()->route()`, `str()->slug()`), never chain two
migration-creating commands, always use enum cases rather than raw strings.
