# 10 - Routes and view identifiers

## Core

| Method | URI | Target |
|--------|-----|--------|
| GET | `/health-check` | closure returning JSON |

Plus `/up`, declared by `withRouting(health: '/up')` and used by the container healthcheck.

## Users

Guests:

| Method | URI | Target |
|--------|-----|--------|
| GET | `/` | the login view |

Authenticated and verified:

| Method | URI | Name | View |
|--------|-----|------|------|
| GET | `/` | redirect to `profile` | |
| GET | `/profile` | `profile` | `users::profile` |
| GET | `/users` | `users.index` | `users::list-users` |
| GET | `/users/create` | `users.create` | `users::create-user`, dialog over `users.index` |
| POST | `/users` | `users.store` | |
| GET | `/users/{user}/edit` | `users.edit` | `users::edit-user`, dialog over `users.index` |
| PUT/PATCH | `/users/{user}` | `users.update` | |
| DELETE | `/users/{user}` | `users.destroy` | |
| POST | `/users/bulk-delete` | `users.bulk-delete` | |
| POST | `/users/deactivate` | `users.deactivate` | |

## Authentication

No route file. Everything comes from Fortify, with views bound in
`AuthenticationServiceProvider`:

| Fortify view | Hybridly view |
|--------------|---------------|
| `loginView` | `authentication::login` |
| `registerView` | `authentication::register` |
| `requestPasswordResetLinkView` | `authentication::forgot-password` |
| `resetPasswordView` | `authentication::reset-password` |
| `verifyEmailView` | `authentication::verify-email` |

Login is rate limited to five attempts per minute, keyed on the identifier and the IP. Enabled
Fortify features: registration, password reset, email verification, profile update, password
update. **No two-factor authentication.**

Only the `laravel/fortify` vendor routes are exposed to the front-end, per `config/hybridly.php`.

## Table actions

Table actions post to a dedicated endpoint, `invoke`, named `hybridly.action.invoke`, on the
`web` middleware group.

## Identifiers to files

`ModuleComponentLoader` maps `{module}::{name}` to
`modules/{Module}/Resources/Views/{name}.view.vue`, and layouts to
`modules/{Module}/Resources/Layouts/{name}.layout.vue`.

```
users::list-users       -> modules/Users/Resources/Views/list-users.view.vue
authentication::login   -> modules/Authentication/Resources/Views/login.view.vue
core::main              -> modules/Core/Resources/Layouts/main.layout.vue
```
