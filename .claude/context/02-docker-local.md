# 02 - Local environment

Source of truth: the companion Docker repository, reached through `DOCKER_DIRECTORY`. Its own
`.claude/context/` covers it in depth. Complementary reference: `.claude/DOCKER_ARCHITECTURE.md`.

## Shape

Containers come in two groups.

**Shared**, started once per machine and serving every project, named after `localdev`:

| Container | Image | Role |
|-----------|-------|------|
| `localdev-traefik` | `traefik:v3.7.13` | Reverse proxy, TLS termination, dashboard |
| `localdev-dnsmasq` | `dockurr/dnsmasq:2.93` | Wildcard DNS for `*.test`, bound to `127.0.0.1:53` |

**Per project**, prefixed with the project name:

| Container | Image | Role |
|-----------|-------|------|
| `{project}-hybridly` | built locally on `dunglas/frankenphp:php8.5` | PHP, Octane, Node, Vite |
| `{project}-mysql` | built locally on `mysql:9.7.2` | Database |
| `{project}-redis` | `redis:8.10.2` | Cache, sessions, queue |
| `{project}-mail` | `axllent/mailpit:v1.31.2` | SMTP and inbox |
| `{project}-rustfs` | `rustfs/rustfs:latest` | S3-compatible storage |
| `{project}-rustfs-init` | minio client | Creates the bucket, then exits |

Each project joins its own `project.{name}` network and the shared `localdev` network, so the
single Traefik can reach it.

## The application container

One container runs both Octane and Vite under supervisor. This is deliberate: Hybridly's
`vite-plugin-run` executes Artisan commands from the Vite dev server, so Vite needs the `php`
binary and the Laravel code in the same filesystem.

Xdebug is installed and set to `start_with_request=yes`, so it runs on every request. It is the
first thing to suspect when the application feels slow. `XDEBUG_MODE=off` in the container
environment disables it without a rebuild.

## URLs

| URL | Service |
|-----|---------|
| `https://app.{project}.test` | Application |
| `https://mail.{project}.test` | Mailpit |
| `https://rustfs.{project}.test` | RustFS console |
| `https://storage.{project}.test` | RustFS S3 API |
| `https://traefik.localdev.test` | Traefik dashboard |

Vite is published directly on port 5173, not proxied.

## Commands

Backend: `artisan cmd="..."`, `cache-clear`, `composer cmd="..."`, `fresh [seed=1]`, `migrate`,
`phpstan`, `pint`, `seed module=X class=Y`, `test [filter=X]`, `tinker`.

Frontend: `eslint`, `pnpm cmd="..."`, `taze`, `taze-major`, `taze-write`, `taze-write-major`,
`vitest`, `vue-tsc`.

Docker: `build`, `build-clean`, `destroy`, `logs [svc=X]`, `ps`, `purge`, `rebuild`,
`rebuild-clean`, `reset`, `restart`, `shell`, `start`, `stop`.

Shared infrastructure: `shared-start`, `shared-stop`, `shared-restart`, `shared-ps`,
`shared-logs [svc=X]`.

Install and DNS: `install`, `setup-dns`, `restore-dns`, `update-certificates`,
`generate-certificate name=X`, `list-certificates`.

`make help` prints everything grouped. There is no double-negative flag any more: `make stop`
always keeps the data, `make reset` always deletes it, `build-clean` always ignores the cache.

**Never run php, composer, node or pnpm directly on the host.**

## Coming back after a pause

```bash
make start                                # brings up the shared stack, then the project
make ps
resolvectl query app.{project}.test       # Linux, check DNS
make setup-dns                            # if it no longer resolves, needs sudo
make migrate
```

`make setup-dns` is the one command that needs elevated privileges, and it is the one thing an
agent cannot run for you.
