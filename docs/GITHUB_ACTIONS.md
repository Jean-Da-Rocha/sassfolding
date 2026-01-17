# GitHub Actions CI/CD Reference

> **Purpose**: Explains how GitHub Actions are configured, why they differ from local Docker setup, and the philosophy
> behind CI optimization.

## Quick Reference

| Workflow    | Purpose             | Jobs                                   |
|-------------|---------------------|----------------------------------------|
| `tests.yml` | Run test suites     | Feature, Unit, Architecture (parallel) |
| `style.yml` | Code quality checks | Pint, PHPStan, ESLint, vue-tsc         |

## Core Philosophy: CI ≠ Local Dev ≠ Production

### The Three Environments Have Different Goals

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│  Local Dev      │    │   CI (Tests)    │    │   Production    │
├─────────────────┤    ├─────────────────┤    ├─────────────────┤
│ Purpose: DX     │    │ Purpose: QA     │    │ Purpose: Runtime│
│ Optimize: Speed │    │ Optimize: Speed │    │ Optimize: Cost  │
│ Lifespan: Hours │    │ Lifespan: Min   │    │ Lifespan: Days  │
│ Match: Prod     │    │ Match: Runtime  │    │ Source of truth │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

### What Must Match Across All Environments

✅ **Runtime Characteristics** (affects code behavior):

- PHP version (8.5)
- PHP extensions (bcmath, gd, mbstring, pcntl, pdo_mysql, redis, zip)
- Service versions (MySQL 9.5, Redis 8.0)
- Locked dependencies (composer.lock, pnpm-lock.yaml)

### What Can Differ Between Environments

❌ **Implementation Details** (doesn't affect code):

- Base OS (Debian vs Ubuntu)
- How PHP/extensions are installed (compiled vs pre-built)
- Container vs VM
- Process manager (Supervisor vs direct exec)
- Development tooling (Traefik, DNSMasq, Xdebug)

**Why?** Because your tests don't care HOW the environment was built—only that PHP executes correctly, database queries
work, and your business logic is sound.

## Why CI Doesn't Use Docker Containers

### Local Development (Docker)

```dockerfile
# $HOME/sassfolding-docker-local/hybridly/Dockerfile
FROM php:8.5-fpm

# Monolithic container (PHP + Node.js)
# Reason: Hybridly's vite-plugin-run needs PHP binary access
# Trade-off: Slower startup, larger image
# Optimized for: Hot reload, debugging, production parity

RUN docker-php-ext-install -j$(nproc) bcmath gd...  # ~45s compile
RUN pecl install xdebug-3.5.0                        # Local debugging
```

**Local needs**:

- Long-lived processes (Vite dev server, watchers)
- Production parity (same base image as production)
- Interactive debugging (Xdebug)
- Complex tooling (Traefik, DNSMasq, RustFS)

### CI (GitHub Actions)

```yaml
# .github/workflows/tests.yml
runs-on: ubuntu-latest  # No container!

steps:
    -   uses: shivammathur/setup-php@v2
        with:
            php-version: '8.5'
            extensions: bcmath, gd, mbstring, pcntl, pdo_mysql, redis, zip
            tools: composer:v2
            coverage: none
```

**CI needs**:

- Ephemeral execution (one-shot test runs)
- Maximum speed (pre-compiled extensions)
- Minimal complexity (industry-standard tooling)
- Cost efficiency (faster = cheaper)

### Technical Comparison

| Approach                      | Extension Installation    | Maintenance     | GitHub Actions Compatibility |
|-------------------------------|---------------------------|-----------------|------------------------------|
| **Container** (`php:8.5-fpm`) | Compile from source       | Manual updates  | Limited (networking issues)  |
| **shivammathur/setup-php**    | Pre-built binaries        | Auto-maintained | Native support               |

**Key advantage**: Pre-compiled extensions are significantly faster than compiling from source on every run.

### Why shivammathur/setup-php is Best Practice

1. **Industry Standard**: Used by Laravel, Symfony, WordPress, Composer itself
2. **Pre-compiled Extensions**: Cached binaries, no compilation needed
3. **Built for GitHub Actions**: Native integration, no container networking issues
4. **Community Maintained**: PHP 8.5 support added within days of release
5. **Automatic Caching**: Extension binaries cached by GitHub automatically
6. **Simpler**: 5 lines vs 30+ lines of setup

## Workflow Architecture

### Composite Action (DRY Principle)

All jobs use a shared composite action `.github/actions/setup-laravel/action.yml` that handles:
- PHP 8.5 setup with extensions
- Composer dependency caching and installation
- pnpm setup (auto-detects version from package.json)
- Node.js setup with pnpm caching
- pnpm dependency installation
- Optional frontend build artifact download

**This eliminates ~40 lines of duplicated setup code per job.**

### Shared Frontend Build Strategy (tests.yml only)

**Problem**: Building Vite assets separately in each test job is redundant

**Solution**: Build once in tests.yml, share as artifact to test jobs

```yaml
# Job 1: Build (runs first)
build:
    name: Build Frontend Assets
    steps:
        -   uses: ./.github/actions/setup-laravel
        -   run: php artisan hybridly:config  # Generate .hybridly/tsconfig.json
        -   run: pnpm vite build --logLevel error
        -   uses: actions/upload-artifact@v4
            with:
                name: frontend-build
                path: |
                    public/build
                    .hybridly
                retention-days: 1

# Jobs 2-5: Tests/Lint (run in parallel, depend on build)
tests:
    needs: build
    steps:
        -   uses: actions/download-artifact@v4
            with:
                name: frontend-build
                path: .
        # Skip "pnpm vite build" step entirely
```

**What's included in the artifact**:
- `public/build` - Compiled Vite assets (JS, CSS)
- `.hybridly` - Generated TypeScript configuration

**Benefits**:
- Frontend built once, reused by 3 test jobs (Feature, Unit, Architecture)
- Parallel test execution works (each test job downloads artifact independently)
- Deterministic builds (same source → same output across all jobs)
- Eliminates redundant build steps

**Why doesn't style.yml use shared build?**
- Only has 1 lint job (no parallelism to benefit from)
- Building fresh ensures `.hybridly` directory is always present for vue-tsc
- Simpler workflow with fewer dependencies between jobs

### Caching Strategy

#### Composer Dependencies

```yaml
-   name: Get Composer cache directory
    id: composer-cache
    run: echo "dir=$(composer config cache-files-dir)" >> $GITHUB_OUTPUT

-   name: Cache Composer dependencies
    uses: actions/cache@v4
    with:
        path: ${{ steps.composer-cache.outputs.dir }}
        key: composer-${{ runner.os }}-${{ hashFiles('**/composer.lock') }}
        restore-keys: composer-${{ runner.os }}-
```

**Cache key**: Based on `composer.lock` hash
**Cache behavior**:
- **Hit**: Dependencies unchanged → restore from cache
- **Miss**: Dependencies changed → download fresh, rebuild cache

#### pnpm Dependencies

```yaml
-   uses: pnpm/action-setup@v4
    # No version specified - automatically reads from package.json "packageManager" field

-   uses: actions/setup-node@v4
    with:
        node-version: 24
        cache: 'pnpm'  # Automatic caching!

-   run: pnpm install --frozen-lockfile
```

**Version management**: `pnpm/action-setup@v4` automatically reads the pnpm version from `package.json` (`"packageManager": "pnpm@10.28.0"`)
**Cache key**: Automatic (based on pnpm-lock.yaml)
**Cache behavior**: Similar to Composer - hits when lockfile unchanged, misses when dependencies change

#### PHPStan Analysis Results

```yaml
-   name: Cache PHPStan results
    uses: actions/cache@v4
    with:
        path: storage/phpstan
        key: phpstan-${{ runner.os }}-${{ hashFiles('**/composer.lock', 'phpstan.neon', 'phpstan-baseline.neon') }}
        restore-keys: phpstan-${{ runner.os }}-
```

**Cache key**: Based on composer.lock + PHPStan config files
**Cache behavior**:
- **Exact hit**: Dependencies and config unchanged → full cache restore
- **Partial hit**: Uses restore key (`phpstan-Linux-`) if exact key misses
- **No hit**: First run or significant changes → full analysis, rebuild cache

**Expected log on first run:**
```
Cache not found for input keys: phpstan-Linux-<hash>, phpstan-Linux-
```
This is normal - GitHub Actions checks both the exact key and partial match. Subsequent runs will hit cache if files are unchanged.

**CRITICAL**: Never use `github.sha` in cache keys! It changes every commit, preventing any cache hits.

## Workflow Details

### tests.yml - Test Suite Execution

**Jobs**: 3 parallel jobs (Feature, Unit, Architecture)

**Matrix Strategy**:

```yaml
strategy:
    fail-fast: false  # Continue all jobs even if one fails
    matrix:
        testsuite: [ Feature, Unit, Architecture ]
```

**Why fail-fast: false?**
See all test failures at once, not just the first one.

**Services**:

```yaml
services:
    mysql:
        image: mysql:9.5
        # Health checks ensure DB ready before tests start

    redis:
        image: redis:8.0
        # Used for caching, sessions, queues in tests
```

**Build job steps:**
1. Setup Laravel environment (composite action: PHP, Composer, pnpm, Node.js)
2. Generate Hybridly config: `php artisan hybridly:config`
3. Build frontend: `pnpm vite build`
4. Upload artifact: `public/build` + `.hybridly`

**Test job steps (3 parallel jobs):**
1. Setup Laravel environment (composite action)
2. Download pre-built frontend assets (from build job)
3. Setup Laravel (.env.ci, generate key)
4. Run migrations
5. Execute test suite: `php artisan test --testsuite=${{ matrix.testsuite }}`

### style.yml - Code Quality Checks

**Jobs**: 1 job (Lint) that runs multiple linters

**Steps**:

1. Setup environment (PHP, Composer, pnpm, Node.js)
2. **Build frontend assets** - Creates `.hybridly` directory needed for vue-tsc
3. Setup Laravel (.env.ci, generate key)
4. Run migrations (needed for IDE Helper generation)
5. Generate IDE Helper files (for PHPStan accuracy)
6. **Run Laravel Pint** - PSR-12 code style
7. **Run PHPStan** - Static analysis (level 9, with caching)
8. **Run ESLint** - JavaScript/TypeScript linting
9. **Run vue-tsc** - Vue type checking (uses `.hybridly/tsconfig.json`)

**Why build in lint job instead of sharing artifact?**
- vue-tsc needs `.hybridly/tsconfig.json` generated during build
- Only 1 job, so no time savings from artifact sharing
- Building fresh is simpler and more reliable

**Build steps:**
1. `php artisan hybridly:config` - Generates `.hybridly/tsconfig.json` and type definitions
2. `pnpm vite build` - Compiles frontend assets using generated config

**Expected warnings during build:**
- `Duplicated imports "TypeName"` - Hybridly's auto-import may detect types in multiple locations. This is informational and doesn't affect the build.

**Why generate IDE Helpers in CI?**
PHPStan needs accurate type information for Laravel magic methods. Without IDE Helpers, you get false positives.

## Optimizations Implemented

The CI workflow includes several optimizations to reduce redundant work and improve efficiency:

| Optimization           | Purpose                                                    | Status         |
|------------------------|------------------------------------------------------------|----------------|
| Composer caching       | Avoid re-downloading packages when composer.lock unchanged | ✅ Implemented  |
| pnpm caching           | Avoid re-downloading packages when pnpm-lock.yaml unchanged| ✅ Implemented  |
| PHPStan cache fix      | Cache analysis results to avoid re-analyzing same code     | ✅ Implemented  |
| Shared frontend build  | Build once, share to 3 test jobs (tests.yml only)         | ✅ Implemented  |
| shivammathur/setup-php | Use pre-compiled extensions instead of compiling on every run | ✅ Implemented  |
| Composite action       | Centralize setup steps to avoid duplication                | ✅ Implemented  |

**Key principle**: Cache what doesn't change, share what can be reused, parallelize what's independent.

## Common Pitfalls & Solutions

### ❌ Don't: Use github.sha in cache keys

```yaml
# BAD: Never hits cache (sha changes every commit)
key: phpstan-${{ github.sha }}
```

### ✅ Do: Use lockfile hashes

```yaml
# GOOD: Hits cache when dependencies unchanged
key: phpstan-${{ hashFiles('**/composer.lock', 'phpstan.neon') }}
```

---

### ❌ Don't: Build frontend in every job

```yaml
# BAD: Wastes 135s building 4 times
steps:
    -   run: pnpm vite build
```

### ✅ Do: Build once, share artifact

```yaml
# GOOD: Build once in separate job, download in others
needs: build
steps:
    -   uses: actions/download-artifact@v4
```

---

### ❌ Don't: Install dependencies without caching

```yaml
# BAD: Downloads 414KB every run
-   run: composer install
```

### ✅ Do: Cache dependency downloads

```yaml
# GOOD: Cache Composer's download cache
-   uses: actions/cache@v4
    with:
        path: ${{ steps.composer-cache.outputs.dir }}
        key: composer-${{ hashFiles('**/composer.lock') }}
```

---

### ❌ Don't: Use container for CI

```yaml
# BAD: Slow startup, manual extension compilation
container:
    image: php:8.5-fpm
steps:
    -   run: docker-php-ext-install -j$(nproc) bcmath...
```

### ✅ Do: Use shivammathur/setup-php

```yaml
# GOOD: Pre-compiled extensions, fast startup
steps:
    -   uses: shivammathur/setup-php@v2
        with:
            php-version: '8.5'
            extensions: bcmath, gd, mbstring, pcntl, pdo_mysql, redis, zip
```

## Differences from Local Development

| Aspect         | Local (Docker)        | CI (GitHub Actions) | Why Different?          |
|----------------|-----------------------|---------------------|-------------------------|
| **Base**       | php:8.5-fpm container | ubuntu-latest VM    | CI optimized for speed  |
| **Extensions** | Compiled from source  | Pre-built binaries  | Faster startup          |
| **Node.js**    | In PHP container      | Separate setup      | Simpler in CI           |
| **Frontend**   | Vite dev server       | Static build        | No HMR needed           |
| **Xdebug**     | Enabled               | Disabled            | No debugging in CI      |
| **Traefik**    | Yes                   | No                  | No reverse proxy needed |
| **DNSMasq**    | Yes                   | No                  | No local DNS needed     |
| **RustFS**     | Yes                   | No                  | No S3-compatible needed |
| **Supervisor** | Yes                   | No                  | Direct exec sufficient  |

**All differences are intentional and don't affect test validity.**

## Monitoring & Maintenance

### What to Monitor

- **Cache hit rates**: Check if Composer/pnpm caches are being utilized effectively
- **Job duration**: Watch for unexpected increases that might indicate issues
- **Total workflow time**: Track trends to identify performance degradation
- **Failure patterns**: Identify flaky tests, dependency conflicts, or infrastructure issues

### When to Update

- **PHP minor version bump**: Update shivammathur/setup-php version
- **Service version changes**: Update MySQL/Redis image tags
- **Dependency updates**: Automatic cache miss, will rebuild
- **New linters**: Add to style.yml

### Cost Awareness

- GitHub Actions minutes are free for public repos (limited for private)
- Artifact storage: 1-day retention keeps costs minimal
- Faster CI = fewer minutes used = lower cost

## Composite Action for DRY Setup

To avoid code duplication, all workflows use a shared composite action: `.github/actions/setup-laravel/action.yml`

**What it does**:
1. Setup PHP 8.5 with extensions (shivammathur/setup-php)
2. Cache and install Composer dependencies
3. Setup pnpm (version from package.json)
4. Setup Node.js with pnpm caching
5. Install pnpm dependencies
6. Optionally download pre-built frontend assets (`public/build` + `.hybridly` TypeScript config)

**Usage in workflows**:
```yaml
# Build job (no download)
- uses: ./.github/actions/setup-laravel

# Test/Lint jobs (with download)
- uses: ./.github/actions/setup-laravel
  with:
    download-build: 'true'
```

**Benefits**:
- ✅ Single source of truth for setup steps
- ✅ Reduces ~40 lines per job to 2-3 lines
- ✅ Easier to maintain and update
- ✅ Consistent environment across all jobs

## Potential Future Improvements

The following optimizations are possible but not currently implemented:

- **Vite cache** - Cache `node_modules/.vite` for faster subsequent builds
- **Service optimization** - Use Alpine-based images for MySQL/Redis (smaller, faster startup)

**Why not implemented?**
Diminishing returns. The major bottlenecks have been addressed with the current optimizations. Additional improvements would add complexity for minimal benefit.

## Related Documentation

- **CI Optimization Plan**: `CI_OPTIMIZATION_PLAN.md` - Detailed optimization strategy and analysis
- **Docker Architecture**: `docs/DOCKER_ARCHITECTURE.md` - Local dev and production Docker setup
- **Claude Instructions**: `docs/CLAUDE.md` - Project structure and coding guidelines

## References

- [GitHub Actions Caching](https://docs.github.com/en/actions/using-workflows/caching-dependencies-to-speed-up-workflows)
- [shivammathur/setup-php](https://github.com/shivammathur/setup-php)
- [pnpm GitHub Action](https://github.com/pnpm/action-setup)
- [Workflow Artifacts](https://docs.github.com/en/actions/using-workflows/storing-workflow-data-as-artifacts)
