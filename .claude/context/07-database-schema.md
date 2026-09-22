# 07 - Database schema

MySQL 9.7. Migrations live per module in `modules/{Module}/Database/Migrations/` and are loaded by
the module service provider. Nothing sits in a root `database/` directory.

## `users` (Users module)

| Column | Type | Notes |
|--------|------|-------|
| id | bigint auto | |
| email | string | unique |
| email_verified_at | timestamp | nullable. Doubles as the activation flag: `DeactivateUserController` nulls it |
| name | string | |
| password | string | cast `hashed` |
| remember_token | string | |
| created_at, updated_at | timestamps | |

No soft delete. An appended `name_initial` accessor is exposed through `$appends`.

## `password_reset_tokens` (same migration)

`email` as primary key, `token`, nullable `created_at`.

That is the whole schema. The organizations, projects and tasks tables went away with the demo
modules.

## Factory

`UserFactory` creates a verified user with the password `password`, cached in a static property.
The `unverified()` state nulls `email_verified_at`.

## Seeder

`UserSeeder` creates 100 users:

```bash
make seed module=Users class=UserSeeder
```

There is no global `DatabaseSeeder`, so `make fresh seed=1` seeds nothing.

## Rules

**Never change the schema or a migration without being asked.** Before writing a test or a query,
check defaults, nullability and the real relationship names on the model rather than guessing them
from column names.
