# 00 - Project overview

## What this is

A reusable scaffolding for SaaS projects. Not a product: an opinionated base (tooling, structure,
conventions) that gets cloned to start something new.

The application repository holds the Laravel and Vue code. A companion repository holds the
Dockerfiles, compose files and Makefiles, and is linked through the `DOCKER_DIRECTORY`
environment variable exported from the shell profile.

The application's own `Makefile` is ten lines: it includes `$(DOCKER_DIRECTORY)/make/main.mk`.
Its `docker-compose.yml` only includes the per-project service files. **Infrastructure changes
belong to the Docker repository**, but `make` commands are always run from the application
directory.

## Modules

Four modules, in dependency order:

| Type | Module | Holds |
|------|--------|-------|
| Foundation | **Core** | Shared data, enums, layouts, navigation, the component loader |
| Infrastructure | **Datatables** | Reusable table system on top of Hybridly Tables |
| Domain | **Users** | User CRUD, profile, password, table |
| Feature | **Authentication** | Fortify views: login, register, reset, verification |

Two demo modules, Organizations and Projects, were removed. They existed to show relationship
columns, enum badges and multi-select filters. `docs/DATATABLE.md` still uses that domain in its
examples, with a note saying it is illustrative only.

## What the scaffolding does not have

- **No authorization at all**: no Policy, no Gate, no `authorize()` call. Any verified logged-in
  user can act on any resource. This is the largest gap.
- No multi-tenancy.
- No global `DatabaseSeeder`, so `make fresh seed=1` seeds nothing. Run the module seeder directly.
- The `prod` stage of the application image is a skeleton: no `COPY`, no dependency install, no
  asset build.
- RustFS is provisioned but nothing uploads to it.
- `app/` is empty; everything lives in `modules/`.

## History worth knowing

| When | What |
|------|------|
| February 2026 | Organizations and Projects modules added, datatable features built out |
| April 2026 | Laravel 13 and Vite 8, Hybridly 0.10.0-beta.14, datatables reworked on native APIs |
| May 2026 | Dependency bump, then the project sat idle |
| September 2026 | Demo modules removed, Hybridly taken to beta.36, datatables retyped, dependencies bumped, Vitest suite added, Traefik and dnsmasq made shared |
