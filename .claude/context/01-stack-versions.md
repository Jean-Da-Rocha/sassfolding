# 01 - Stack and versions

## Backend

| Package | Constraint | Note |
|---------|-----------|------|
| php | `^8.3` | Runtime is 8.5 in Docker and CI |
| laravel/framework | `^13.0` | |
| hybridly/laravel | `0.10.0-beta.36` | **Pinned exactly**, must match the JS package |
| laravel/fortify | `^1.21` | Login, register, reset, email verification |
| laravel/octane | `^2.13` | FrankenPHP server |
| spatie/laravel-data | `^4.5` | DTOs |
| spatie/laravel-typescript-transformer | `^2.4` | **Pinned to v2**, see below |
| pestphp/pest | `^4.0` | **Pinned to v4**, see below |

PSR-4: `App\` to `app/` (empty) and `Modules\` to `modules/`.

## Frontend

| Package | Constraint | Note |
|---------|-----------|------|
| vue | `^3.5` | Composition API, `<script setup>` |
| @nuxt/ui | `^4.11` | Nuxt UI v4 |
| hybridly | `0.10.0-beta.36` | **Pinned exactly**, mirrors the PHP package |
| @internationalized/date | `~3.12.4` | Pinned in tilde form, also as a pnpm override |
| vite | `^8.3` | |
| tailwindcss | `^4.3` | Config lives in `tailwind.css`, there is no `tailwind.config.js` |
| typescript | `^5.9` | **Pinned to v5**, see below |
| eslint | `^10` | with `@antfu/eslint-config` |
| vitest | `^5` | Front-end test runner |
| @vueuse/core | `^15` | |

Package manager: **pnpm 12**, declared in `packageManager` and mirrored by `PNPM_VERSION` in the
Docker repository.

## Deliberate pins, and what actually blocks them

1. **hybridly beta.36 on both sides.** Still a beta, the API moves between releases. The PHP and
   JS packages must stay on the same version.
2. **spatie/laravel-typescript-transformer v2.** v3 installs fine, but Hybridly calls
   `$config->outputFile()`, a v2 method that no longer exists, so type generation dies
   immediately. Blocked until Hybridly migrates.
3. **pest v4.** Pest 5 requires `symfony/process ^8.1`, which the transformer v2 forbids. So this
   pin is a consequence of the previous one, not a choice.
4. **typescript v5.** No release of `typescript-eslint`, canary included, accepts TypeScript
   beyond `<6.1.0`. Installing TypeScript 7 makes the parser throw at load time and takes all of
   ESLint down. Blocked until `typescript-eslint` ships support.

`@internationalized/date` used to be pinned to `~3.11` over a `CalendarDate` type conflict. That
conflict is fixed upstream and the pin was lifted to `~3.12.4`.

## pnpm 12 and the supply-chain policy

pnpm 12 refuses any package published less than about a day ago (`minimumReleaseAge`). When a
dependency bump pulls a freshly published transitive package, `pnpm install` fails with
`ERR_PNPM_MINIMUM_RELEASE_AGE_VIOLATION`. The fix is to rebuild the resolution
(`pnpm clean --lockfile` then `pnpm install`), which makes pnpm pick versions that are old enough.

pnpm 12 also replaced `onlyBuiltDependencies` and `ignoredBuiltDependencies` with a single
`allowBuilds` map, and removed the `--no-install` flag that the Husky hooks used. Both are handled.

## Updating

```bash
make taze              # minor and patch
make taze-major        # including majors
make taze-write        # write and install
make composer cmd="update"
```
