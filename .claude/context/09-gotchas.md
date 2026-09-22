# 09 - Traps and known debt

Statements of fact, not a list of things to fix.

## Environment

- The stack must be running for anything to work, including the Git hooks, which shell into the
  container.
- `make setup-dns` needs elevated privileges. It is the one step an agent cannot perform.
- On Linux, `systemd-resolved` does not bind a server to a domain inside its global scope. A
  machine that already pins its own resolver with `Domains=~.`, which any VPN or pinned DNS setup
  does, will answer `.test` queries from that resolver instead of dnsmasq. This is why
  `make setup-dns` prefers a dedicated NetworkManager profile when NetworkManager is available.
- Traefik and dnsmasq are shared across projects. Stopping them takes every project offline.
- MySQL, Redis and Vite are still published per project, so running two projects at once means
  overriding those three ports in the second project's Makefile.
- `make purge` prunes the whole machine, not just this project.

## Laravel and Eloquent

- `Model::shouldBeStrict()` is on. Any access to an unselected attribute or any lazy load throws.
  This is the usual cause of failures in table queries.
- `Model::unguard()` is on. `$fillable` documents intent, it protects nothing. Validation happens
  in the DTOs and the Fortify actions.
- Dates are `CarbonImmutable`. `$date->addDay()` returns a new instance.
- `URL::forceScheme('https')` applies in tests too.

## Hybridly

The 0.10 beta line changed a lot, and older tutorials are actively misleading:

- `hybridly('view', [...])` no longer works, it is `hybridly()->view(...)`.
- `->base('route')` became `->configureDialog(baseUrl: route('route'))`.
- `Hybridly\View\Factory` became `Hybridly\HybridResponseFactory`.
- The middleware is `final` and carries no `share()`. Sharing goes through a separate middleware.
- `loadViewsFrom` and `loadLayoutsFrom` are gone, replaced by a `ComponentLoader`.
- `form.submit` and `useDialog().close` take options and cannot be used as event handlers.
- Filter query parameters are objects, not scalars.
- PHP 8.5 raises a deprecation inside Hybridly's `HasRefiners`, passing null to `explode()`. It is
  upstream and harmless, but it shows up in verbose output.

## Vite dev server

The `dev` script binds every interface so Docker can publish the port, but `0.0.0.0` is a listen
address, not a connectable host. Firefox rejects it outright with `Module source URI is not
allowed`, while Chrome silently remaps it to localhost, so the bug only shows up in one browser.

The host written to `public/hot` comes from `server.hmr.host`, then `server.host`, then the bound
address. `vite.config.ts` therefore sets an HMR host derived from `APP_URL`, which is also covered
by the wildcard certificate. Changing the `dev` script's `--host` value will not fix this on its
own.

## TypeScript

- Generated declarations live in `.hybridly/`, which is not tracked. A missing `php-types.d.ts`
  makes every `Modules.*.Data.*` type unresolvable. Regenerate with
  `make artisan cmd="hybridly:types"`.
- Importing something that is auto-imported is an ESLint error.

## Dependency ceilings

TypeScript is stuck at 5 because no `typescript-eslint` release accepts 6.1 or beyond. Pest is
stuck at 4 because `spatie/typescript-transformer` v2 forbids `symfony/process` 8, and that
package is stuck at v2 because Hybridly calls a v2-only method. None of this is a local choice.

## What is missing from the scaffolding

- **No authorization whatsoever.** No Policy, no Gate, no `authorize()` call anywhere. Any
  verified logged-in user can act on any resource.
- No tests on Core, Authentication, or the `datatable.vue` component itself.
- No global `DatabaseSeeder`.
- The `prod` image stage is a skeleton.
- RustFS is provisioned but unused.

## Non-negotiable project rules

No schema or migration change without being asked. No global architecture refactor. No change to
authentication or authorization without approval. No breaking change. No dependency change that
was not requested. Never run PHP or Node directly on the host.
