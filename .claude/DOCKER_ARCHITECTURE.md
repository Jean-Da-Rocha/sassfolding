# Docker Architecture Reference

> **Purpose**: Technical reference for Claude to understand the Docker setup across environments and build
> production-ready deployments later.

## Environment Matrix

| Aspect                 | Local Dev               | CI (GitHub Actions)         | Production (TBD)          |
|------------------------|-------------------------|-----------------------------|---------------------------|
| **Base Image**         | `php:8.5-fpm`           | `php:8.5-fpm`               | `php:8.5-fpm` (optimized) |
| **Container Strategy** | Monolithic (PHP+Node)   | Monolithic (PHP+Node)       | Separated (PHP-FPM only)  |
| **Node.js Location**   | Inside PHP container    | Manual install in container | Separate build stage      |
| **Proxy**              | Traefik (with TLS)      | None                        | External (TBD)            |
| **DNS**                | DNSMasq (.test domains) | None                        | None                      |
| **Storage**            | RustFS (S3-compatible)  | Filesystem                  | Real S3 or equivalent     |
| **Xdebug**             | Enabled                 | Disabled                    | Disabled                  |
| **Process Manager**    | Supervisor              | Direct exec                 | Supervisor or similar     |

## Local Development Stack

### Source Location

- External project: `$HOME/sassfolding-docker-local`
- Environment variable: `DOCKER_DIRECTORY`
- Dockerfiles and configs managed separately from application code

### Hybridly Container Details

**Dockerfile**: `$HOME/sassfolding-docker-local/hybridly/Dockerfile`

```dockerfile
FROM php:8.5-fpm

# System packages
RUN apt-get install -y curl git gnupg libonig-dev libpng-dev libxml2-dev libzip-dev supervisor unzip

# PHP extensions (compiled)
RUN docker-php-ext-install -j$(nproc) bcmath gd mbstring pcntl pdo_mysql zip
RUN pecl install redis xdebug-3.5.0 && docker-php-ext-enable redis xdebug

# Composer 2.9.2
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer --version=2.9.2

# Node.js 24 via NodeSource repository
RUN curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg
RUN echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_24.x nodistro main" > /etc/apt/sources.list.d/nodesource.list
RUN apt-get update && apt-get install -y nodejs

# pnpm 10.25.0 via corepack
RUN corepack enable && corepack prepare pnpm@10.25.0 --activate
```

**Why monolithic container?**

- Hybridly's `vite-plugin-run` executes Artisan commands from Vite dev server
- Vite needs direct access to `php` binary and Laravel code
- Separation would require complex volume mounting or RPC

**Critical Constraint**: This is a local development limitation. DO NOT carry this pattern to production.

### Service Versions (from `make/infra.mk`)

```makefile
COMPOSER_VERSION = 2.9.2
MYSQL_VERSION = 9.5.0
NODE_VERSION = 24
PHP_VERSION = 8.5
PNPM_VERSION = 10.25.0
REDIS_VERSION = 8.4.0
XDEBUG_VERSION = 3.5.0
```

### Local-Only Services (NOT in CI/Prod)

- **DNSMasq**: Local DNS for `*.test` domain resolution
- **Traefik**: Reverse proxy with automatic SSL cert generation
- **RustFS**: S3-compatible object storage for local file testing
- **Mailpit**: SMTP server for email testing
- **Nginx**: FastCGI proxy (Traefik doesn't support PHP-FPM directly)

## CI Environment (GitHub Actions)

> **For detailed CI documentation**, see `docs/GITHUB_ACTIONS.md`

### Philosophy: CI ≠ Local Development

**Critical Distinction**: CI is optimized for speed and reliability, not production parity.

| Must Match                 | Can Differ                   |
|----------------------------|------------------------------|
| ✅ PHP version (8.5)        | ❌ Base OS (Ubuntu vs Debian) |
| ✅ PHP extensions           | ❌ How extensions installed   |
| ✅ Service versions         | ❌ Container vs VM            |
| ✅ Dependencies (lockfiles) | ❌ Development tooling        |

**Why?** Tests validate code correctness, not environment implementation. As long as runtime characteristics match (PHP
version, extensions, services), tests are valid regardless of how the environment was built.

### Current Implementation (Optimized Jan 2026)

**Workflow Files**:

- `.github/workflows/tests.yml` - Feature, Unit, Architecture tests (3 parallel jobs)
- `.github/workflows/style.yml` - Pint, PHPStan, ESLint, vue-tsc (1 job)

**Setup Strategy**:

```yaml
runs-on: ubuntu-latest  # VM, not container!

steps:
    -   uses: shivammathur/setup-php@v2
        with:
            php-version: '8.5'
            extensions: bcmath, gd, mbstring, pcntl, pdo_mysql, redis, zip
            tools: composer:v2
            coverage: none

services:
    mysql:
        image: mysql:9.5
    redis:
        image: redis:8.0
```

**Why shivammathur/setup-php instead of containers?**

- ✅ Pre-compiled extensions (~65s faster per job)
- ✅ Industry standard (used by Laravel, Symfony, WordPress)
- ✅ Built-in caching and GitHub Actions integration
- ✅ Auto-maintained (PHP 8.5 support added within days)
- ✅ Same runtime behavior as local (PHP 8.5, same extensions)

**Key Differences from Local** (intentional, don't affect tests):

- No Xdebug (performance)
- No Supervisor (direct command execution)
- No Traefik/DNSMasq/RustFS (local-only services)
- Frontend built separately (pnpm vite build), not dev server

### Performance Optimizations (Implemented)

**1. Shared Frontend Build**

- Build job runs once, uploads artifact
- Test/lint jobs download pre-built assets
- Saves: ~135s per PR

**2. Dependency Caching**

- Composer cache: ~60s saved per job
- pnpm cache: ~50s saved per job
- PHPStan cache: ~30s saved (with correct cache key)

**3. Pre-compiled PHP Extensions**

- shivammathur/setup-php: ~65s saved per job vs manual compilation

**Total Performance**: ~4 minutes per PR (75% faster than original ~16 minutes)

## Production Environment (Future Work)

### Design Constraints

**User Requirements**:

1. Cannot use same Docker setup as local (no Traefik, RustFS, separate images)
2. Must use pre-built Vite assets (no dev server)
3. Should stay aligned with local PHP/MySQL/Redis versions where possible

### Production Architecture (Proposed)

**Multi-stage Dockerfile Strategy**:

```dockerfile
# Stage 1: Build frontend assets
FROM node:24 AS frontend
WORKDIR /app
COPY package.json pnpm-lock.yaml ./
RUN corepack enable && corepack prepare pnpm@10.25.0 --activate
RUN pnpm install --frozen-lockfile --prod
COPY resources/ vite.config.js ./
RUN pnpm vite build

# Stage 2: PHP runtime (production)
FROM php:8.5-fpm
RUN apt-get update && apt-get install -y libonig-dev libpng-dev libxml2-dev libzip-dev
RUN docker-php-ext-install -j$(nproc) bcmath gd mbstring pcntl pdo_mysql zip
RUN pecl install redis && docker-php-ext-enable redis
COPY --from=frontend /app/public/build /var/www/html/public/build
COPY ../../docs /var/www/html
RUN composer install --no-dev --optimize-autoloader
```

**Key Production Differences**:

- **Separate build stage**: Node.js only in build, not runtime
- **No Xdebug**: Remove PECL xdebug install
- **No dev dependencies**: `composer install --no-dev`
- **No Supervisor in Dockerfile**: Managed by orchestrator (Kubernetes, ECS, etc.)
- **Optimized layers**: Smaller final image size

### Production Deployment Options

**Option A: Traditional Server**

- Nginx/Caddy as reverse proxy (replaces Traefik)
- PHP-FPM behind proxy
- Separate queue worker processes
- Real S3 for storage (replaces RustFS)

**Option B: Container Orchestration**

- Kubernetes/ECS/Fargate
- Separate services: web, queue, scheduler
- Managed database (RDS, Cloud SQL)
- Managed Redis (ElastiCache, MemoryStore)

**Option C: PaaS**

- Laravel Vapor (AWS Lambda)
- Platform.sh, Render, Fly.io
- Less control, more convenience

### Environment Configuration

**Required .env differences**:

```bash
# Local
APP_ENV=local
APP_DEBUG=true
VITE_DEV_SERVER_URL=https://app.sassfolding.test:5173
FILESYSTEM_DISK=s3  # RustFS
MAIL_MAILER=smtp    # Mailpit

# Production
APP_ENV=production
APP_DEBUG=false
VITE_DEV_SERVER_URL=  # Empty, use pre-built assets
FILESYSTEM_DISK=s3    # Real S3
MAIL_MAILER=ses       # Real SES
```

## Critical Implementation Notes

### DO NOT Do These in CI/Prod:

1. ❌ Install Xdebug (massive performance hit)
2. ❌ Run Vite dev server (use `pnpm vite build`)
3. ❌ Include Node.js in production PHP container
4. ❌ Use `composer install` without `--optimize-autoloader` in prod
5. ❌ Switch away from `php:8.5-fpm` base image

### MUST Maintain Consistency:

1. ✅ PHP 8.5 across all environments
2. ✅ Same PHP extensions (bcmath, gd, mbstring, pcntl, pdo_mysql, redis, zip)
3. ✅ MySQL 9.x and Redis 8.x service versions
4. ✅ Composer 2.9.x and pnpm 10.x
5. ✅ Laravel environment variable patterns

### Performance Targets:

- **CI**: <6 min per PR (after Phase 1 optimizations)
- **Local**: Hot reload <200ms
- **Production**: Response time <100ms p95

## File Locations

- Local Docker configs: `$HOME/sassfolding-docker-local/`
- CI workflows: `.github/workflows/`
- This reference: `.claude/DOCKER_ARCHITECTURE.md`
- CI implementation guide: `docs/GITHUB_ACTIONS.md`
- CI optimization plan: `CI_OPTIMIZATION_PLAN.md`
