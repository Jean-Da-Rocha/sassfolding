# 08 - Tests, quality and CI

Detailed CI documentation: `docs/GITHUB_ACTIONS.md`.

## PHP tests

Pest 4. `tests/Pest.php` applies `TestCase` and `RefreshDatabase` to `modules/*/Tests`. Three
suites are declared in `phpunit.xml`: Feature, Unit and Architecture, each globbing
`modules/*/Tests/{Suite}`.

```bash
make test
make test filter=UserControllerTest
```

Coverage is thin by design: user controllers and actions, the table page size, and the module
boundaries. Core and Authentication have no tests.

### Hybridly specifics

- A dialog route asserts through `assertHybridDialog(view:, properties:)`; `assertHybridView()`
  returns the base view.
- A non-Hybridly test request gets a 302, not a 303. The 303 conversion only applies to Hybridly
  XHR requests on PUT, PATCH and DELETE.
- The XHR header is `x-hybrid`.
- Filter query parameters are objects, not scalars: `filters[name][value]=...`. Passing a scalar
  silently applies no filter.
- PHPStan cannot see the assertions Hybridly registers through `TestResponse::mixin()` at runtime,
  hence the scoped ignore in `phpstan.neon`.

## Front-end tests

Vitest, configured in `vitest.config.ts`, which reuses the build config so tests get the same
auto-imports. It resolves the config in `build` mode on purpose: the `serve` branch reads TLS
certificates that only exist inside the container.

```bash
make vitest
```

Tests live in `modules/{Module}/Tests/Unit/*.test.ts`. Only the Datatables composables are covered
today.

## Static analysis and style

| Tool | Command | Configuration |
|------|---------|---------------|
| PHPStan and Larastan | `make phpstan` | **level 9**, targets `modules/` |
| Pint | `make pint` | `pint.json` |
| ESLint | `make eslint` | `@antfu/eslint-config` plus sorting rules and Tailwind checks |
| vue-tsc | `make vue-tsc` | extends `.hybridly/tsconfig.json` |

Level 9 demands generic annotations on every Eloquent relation. Regenerate the IDE helper files
after a schema change: `make composer cmd="autocomplete"`.

Run all of them after every commit.

## Git hooks

| Hook | Action |
|------|--------|
| `commit-msg` | commitlint, through `pnpm exec` inside the container |
| `pre-commit` | lint-staged: ESLint on Vue and TS, Pint on PHP |
| `pre-push` | branch name validation |

They all shell into the application container, so **they fail when the stack is down**.

## Conventions

Conventional Commits, enforced. The subject alone, no body. No co-author line. Branch names follow
the regex in `package.json`, which is the source of truth.

## CI

Two workflows on pull requests and pushes to the main branch, with concurrent runs cancelled.

A composite action installs PHP 8.5 with the same extensions as the Docker image, caches Composer
and pnpm, and optionally downloads the shared front-end build.

`tests.yml` builds the assets once, uploads them, then fans out: three PHP suites in a matrix
against MySQL and Redis services, plus a front-end job running Vitest with no services at all.

`style.yml` runs Pint, PHPStan with a cache, ESLint with zero tolerance for warnings, and vue-tsc.

CI does not reproduce Docker. It runs on a plain runner with `shivammathur/setup-php`. What must
match: PHP version, extensions, service versions, lockfiles. What may differ: base OS, how things
are installed, development tooling.
