# Context index

Persistent context for this repository. Read this first to reload the state of the project
without exploring it again.

| File | Contents | When to read |
|------|----------|--------------|
| [00-project-overview.md](00-project-overview.md) | What this is, current state, history | Always |
| [01-stack-versions.md](01-stack-versions.md) | Exact versions, deliberate pins and why | Before touching a dependency |
| [02-docker-local.md](02-docker-local.md) | Local stack, shared infrastructure, make targets | Before running or debugging the environment |
| [03-module-architecture.md](03-module-architecture.md) | Modules, boundaries, providers, component loader | Before creating or changing a module |
| [04-backend-conventions.md](04-backend-conventions.md) | Laravel, Data, Actions, Controllers, Hybridly API | Before writing PHP |
| [05-frontend-conventions.md](05-frontend-conventions.md) | Vue, Nuxt UI, auto-imports, navigation, TS | Before writing Vue or TS |
| [06-datatables.md](06-datatables.md) | Datatables internals, composables, tests | Before touching a table |
| [07-database-schema.md](07-database-schema.md) | Schema, factories, seeders | Before writing a test or a query |
| [08-testing-ci.md](08-testing-ci.md) | Pest, Vitest, static analysis, workflows | Before committing or changing CI |
| [09-gotchas.md](09-gotchas.md) | Traps, constraints, known debt | When something behaves unexpectedly |
| [10-routes-map.md](10-routes-map.md) | Routes and Hybridly view identifiers | To map a URL to a file |

## Repository documentation (do not duplicate here)

- `.claude/CLAUDE.md`: project instructions, loaded automatically
- `.claude/DOCKER_ARCHITECTURE.md`: Docker reference across local, CI and production
- `docs/DATATABLE.md`: how to build a table
- `docs/GITHUB_ACTIONS.md`: how the CI pipelines are built
- `README.md`: public presentation and installation

## Companion repository

The Docker setup lives in a separate repository, pointed at by the `DOCKER_DIRECTORY`
environment variable. It carries its own `.claude/context/` covering services, the application
image, the make system, networking and TLS.
