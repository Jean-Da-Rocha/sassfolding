# Docker Architecture Reference

> **Purpose**: Technical reference for Claude to understand the Docker setup across environments. The production
> stage is scaffolded but not deployment-ready yet.

## Environment Matrix

| Aspect                 | Local Dev                    | CI (GitHub Actions)         | Production (TBD)           |
|------------------------|------------------------------|-----------------------------|----------------------------|
| **Base Image**         | `dunglas/frankenphp`         | `shivammathur/setup-php`    | `dunglas/frankenphp`       |
| **Container Strategy** | Monolithic (PHP+Node)        | VM (not container)          | PHP only (multi-stage)     |
| **Node.js Location**   | Inside PHP container (dev)   | Manual install in VM        | Build stage only           |
| **Web Server**         | FrankenPHP + Laravel Octane  | None (test runner)          | FrankenPHP + Octane        |
| **Proxy**              | Traefik (with TLS)           | None                        | External (TBD)             |
| **DNS**                | dnsmasq (wildcard)           | None                        | None                       |
| **Storage**            | RustFS (S3-compatible)       | Filesystem                  | Real S3 or equivalent      |
| **Xdebug**             | Enabled (dev stage)          | Disabled                    | Disabled (prod stage)      |
| **Process Manager**    | Supervisor (Octane + Vite)   | Direct exec                 | Direct Octane              |

## Local Development Stack

### Source Location

- External project: `$HOME/sassfolding-docker-local`
- Environment variable: `DOCKER_DIRECTORY`
- Dockerfiles and configs managed separately from application code

### Container Architecture

Traefik and dnsmasq are **shared**: one instance per machine serves every project, started by
`make shared-start` (which `make start` runs on its own). They are named after
`SHARED_PROJECT_NAME` (`localdev`) instead of the project. Traefik reads the Docker socket, so
one instance already sees every project's containers, and dnsmasq answers the same thing for
all of them. Sharing them frees ports 80, 443 and 53 from any collision between projects.

Every project joins the shared `localdev` network alongside its own `project.{name}` network.

**Shared** (`shared/docker-compose.yml`, project `localdev`):

| Container       | Purpose                                               |
|-----------------|-------------------------------------------------------|
| **Traefik**     | Dynamic reverse proxy with TLS and dashboard          |
| **dnsmasq**     | Wildcard DNS for `*.{project}.test` domains           |

**Per project** (prefixed with `COMPOSE_PROJECT_NAME`):

| Container       | Purpose                                               |
|-----------------|-------------------------------------------------------|
| **Hybridly**    | FrankenPHP + Octane + Node.js (PHP server + Vite dev) |
| **Mailpit**     | Local SMTP server and email inbox                     |
| **MySQL**       | Relational database (local and testing)               |
| **Redis**       | Cache, sessions, and queue driver                     |
| **RustFS**      | S3-compatible object storage                          |
| **RustFS-init** | Init container for bucket creation (exits after run)  |

### Request Flow

```
Browser → Traefik (TLS on :443) → FrankenPHP/Octane (:8000) → Laravel
```

### Hybridly Container Details

**Dockerfile**: `$HOME/sassfolding-docker-local/hybridly/Dockerfile` (multi-stage)

- **base stage**: `dunglas/frankenphp:php8.5` + PHP extensions + Composer
- **dev stage**: base + Xdebug + Node.js 24 + pnpm + supervisor
- **prod stage**: base only (no dev tools), scaffolded and needs `COPY` and dependency install before use

**PHP Extensions**: bcmath, gd, mbstring, pcntl, pdo_mysql, zip, redis (+ xdebug in dev)

**Why monolithic container in dev?**

- Hybridly's `vite-plugin-run` executes Artisan commands from Vite dev server
- Vite needs direct access to `php` binary and Laravel code
- Separation would require complex volume mounting or RPC

**Critical Constraint**: In production, the `prod` stage has no Node.js. Assets are pre-built in CI.

### Service Versions (from `make/infra.mk`)

```makefile
COMPOSER_VERSION = 2.10.3
DNSMASQ_VERSION = 2.93
MYSQL_VERSION = 9.7.2
NODE_VERSION = 24
PHP_VERSION = 8.5
PNPM_VERSION = 12.5.1
REDIS_VERSION = 8.10.2
TRAEFIK_VERSION = v3.7.13
XDEBUG_VERSION = 3.5.3
```

### Local-Only Services (NOT in CI/Prod)

- **Traefik**: Reverse proxy with automatic TLS certificate handling (shared)
- **dnsmasq**: Wildcard DNS server for local domain resolution (shared)
- **RustFS**: S3-compatible object storage for local file testing
- **Mailpit**: SMTP server for email testing

### DNS Resolution

A shared dnsmasq container provides wildcard DNS for all `*.{project}.test` domains, published on
`127.0.0.1:53` only. `make setup-dns` then points the OS resolver at it, picking the mechanism that
fits the system:

- **macOS**: a resolver file at `/etc/resolver/test`. macOS resolves per domain by design, so this
  never conflicts with the rest of the DNS configuration.
- **Linux with NetworkManager**: a dedicated profile named `test-dns`, carried by a dummy `test0`
  interface, holding `ipv4.dns 127.0.0.1` and the routing domain `ipv4.dns-search ~test`. A routing
  domain attached to a link beats a global one, which is how Tailscale and corporate VPNs route
  their own domains.
- **Linux without NetworkManager**: a systemd-resolved drop-in at
  `/etc/systemd/resolved.conf.d/test.conf`, holding the same server and routing domain globally.

The NetworkManager path exists because systemd-resolved does not bind a server to a domain inside
its global scope: every global server is a candidate for every global routing domain. A machine
that already pins its own resolver with `Domains=~.` would therefore answer `.test` queries from
that resolver instead of dnsmasq. `make setup-dns` warns when it detects this and cannot avoid it.

The TLD is `.test` (IETF-reserved, RFC 6761), defined as `DNS_DOMAIN` in `make/infra.mk`.

## CI Environment (GitHub Actions)

> **For detailed CI documentation**, see `docs/GITHUB_ACTIONS.md`

### Philosophy: CI ≠ Local Development

**Critical Distinction**: CI is optimized for speed and reliability, not production parity.

| Must Match                 | Can Differ                   |
|----------------------------|------------------------------|
| PHP version (8.5)          | Base OS (Ubuntu vs Debian)   |
| PHP extensions             | How extensions installed     |
| Service versions           | Container vs VM              |
| Dependencies (lockfiles)   | Development tooling          |

**Why?** Tests validate code correctness, not environment implementation. As long as runtime characteristics match (PHP
version, extensions, services), tests are valid regardless of how the environment was built.

### Current Implementation

**Workflow Files**:

- `.github/workflows/tests.yml` - Feature, Unit, Architecture tests (3 parallel jobs)
- `.github/workflows/style.yml` - Pint, PHPStan, ESLint, vue-tsc (1 job)

**Setup Strategy**:

```yaml
runs-on: ubuntu-latest

steps:
    -   uses: shivammathur/setup-php@v2
        with:
            php-version: '8.5'
            extensions: bcmath, gd, intl, mbstring, pcntl, pdo_mysql, redis, zip
            tools: composer:v2
            coverage: none

services:
    mysql:
        image: mysql:9.7
    redis:
        image: redis:8.10
```

**Key Differences from Local** (intentional, don't affect tests):

- No Xdebug (performance)
- No Supervisor (direct command execution)
- No Traefik/dnsmasq/RustFS (local-only services)
- Frontend built separately (pnpm vite build), not dev server

## Production Environment (Future Work)

### Production Architecture

The Dockerfile includes a scaffolded `prod` stage that only sets the user and CMD:

```dockerfile
FROM base AS prod
# No Node.js, no Xdebug, no supervisor
CMD ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=8000"]
```

**Still needed before this stage is usable**:

- A Node.js build stage to compile frontend assets (`pnpm install` + `pnpm vite build`)
- `COPY` instructions to bake application code into the image
- `COPY --from=build` to bring compiled assets from the build stage
- `composer install --no-dev --optimize-autoloader`
- Laravel cache commands (`config:cache`, `route:cache`, `view:cache`)
- `--max-requests=500` flag to prevent memory leaks

**Key Production Differences** (once implemented):

- **FrankenPHP + Octane**: Persistent workers for high performance
- **No Node.js**: Assets pre-built in a build stage, copied to image
- **No Xdebug**: Not installed in base or prod stage
- **No dev dependencies**: Only production Composer packages
- **No Supervisor**: Octane runs directly as the container CMD
- **No bind mounts**: All code baked into the image

### Deployment Options

**Option A: Container Orchestration** (Kubernetes, ECS, Fargate)
- Separate services: web, queue, scheduler
- Managed database (RDS, Cloud SQL)
- Managed Redis (ElastiCache, MemoryStore)

**Option B: PaaS** (Laravel Forge, Render, Fly.io)
- Less control, more convenience

## Critical Implementation Notes

### DO NOT Do These in CI/Prod:

1. Install Xdebug (massive performance hit)
2. Run Vite dev server (use `pnpm vite build`)
3. Include Node.js in production runtime image
4. Use `composer install` without `--optimize-autoloader` in prod

### MUST Maintain Consistency:

1. PHP 8.5 across all environments
2. Same PHP extensions (bcmath, gd, intl, mbstring, pcntl, pdo_mysql, redis, zip)
3. MySQL 9.x and Redis 8.x service versions
4. Composer 2.10.x and pnpm 12.x

## File Locations

- Local Docker configs: `$HOME/sassfolding-docker-local/`
- CI workflows: `.github/workflows/`
- This reference: `.claude/DOCKER_ARCHITECTURE.md`
- CI implementation guide: `docs/GITHUB_ACTIONS.md`
