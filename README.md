# Sassfolding

![Sassfolding App](art/sassfolding.png)

## Table of Contents
- [Introduction](#introduction)
- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
- [Know Issues](#known-issues)
- [Contributing](#contributing)
- [Roadmap](#roadmap)

## Introduction

It always has been a real hassle for me to set up a new project: configure tools, linting, install packages and more
importantly: make it collaborative.

For those reasons, I decided to create a reusable boilerplate that allows you to quickly scaffold a project, with
almost zero configuration, thanks to the power of docker and some make commands.

Even if this boilerplate is opinionated in a sense of which tool are used, the overall project architecture and the
strict code rules, I hope you can pick some ideas or find something interesting.

## Features

- 🐳 Docker-first development setup (Laravel, Hybridly, Node, Redis, Mailpit, MinIO, Traefik)
- ⚡ One-liner setup via `make install`
- 🧪 Preconfigured `.env` and `.env.testing` environments
- 🔏 SSL certificates with `mkcert` + `.test` TLD with local DNS resolver
- 🛠️ Built-in support for Husky Git hooks
- 🔐 Secure access to Horizon via custom middleware
- 📦 Modern frontend stack (pnpm, Tailwind CSS, Laravel, Hybridly, etc.)
- ✨ Code linting and formatting preconfigured (ESLint, Pint, etc.)
- 🔎 Robust types enforcement (vue-tsc, PHPStan, laravel typescript-transformer)
- 📱 Fully responsive
- 🌓 Light & Dark Mode

## Requirements

To run this project, you need to have installed:

- Docker (with docker compose >= 2.20.3)
- Git
- Mkcert

## Installation

You should first read the documentation [here](docker/README.md) to get a good understanding on how docker powers this
project.

- ```git clone https://github.com/Jean-Da-Rocha/sassfolding.git```
- ```cd sassfolding && make install```

Based on the `${COMPOSE_PROJECT_NAME}` variable (**sassfolding** in our case), the installation process will
automatically do the following:

- Create the dedicated env files (.env and .env.testing), replacing dynamic variables in those files.
- Generate SSL certificates for HTTPS
- Configure husky hooks
- Build the docker images
- Install pnpm & composer dependencies
- Generate keys for both .env and .env.testing files
- Configure a DNS resolver based on .test Top-Level Domain (TLD)
- Creates the `sassfolding` and `sassfolding_testing` databases
- Start the containers in detached mode

You can always run ```make help``` in your console to see which commands are available.

## Usage

Once the docker container are running, you can access the following URL:

- https://app.sassfolding.test - The main application
- https://horizon.sassfolding.test - The Laravel Horizon dashboard to monitor your Redis queues
- https://mail.sassfolding.test - The Mailpit dashboard to receive your mails locally
- https://minio.sassfolding.test - The MinIO dashboard to manage files, folders and buckets
- https://traefik.sassfolding.test - The Traefik dashboard to view your entrypoints, routes etc.

*Note: There is a middleware called `EnsureValidHorizonUri` to make sure Horizon dashboard and its API
remain protected and scoped*

## Known issues

- The datatable module does not fully support Hybridly **inline** and **bulk** actions yet
- The eslint-plugin-tailwindcss is not working with the project since it has not been updated to Tailwind 4 yet
- There is an error (can't resolve reference #/definitions/max-line-length-requires-line-length-type from id sort-imports
) with the eslint-sort-import plugin, which will be fixed in a future update

## Contributing

You're welcome to open issues or pull requests if you find bugs or want to suggest improvements.

## Roadmap

Here are some planned improvements and upcoming features:

- Improve Hybridly datatable support for inline and bulk actions
- Improve datatable pagination
- Use a modular back-end architecture
- Add vitest for testing front-end logic and components
- Upgrade eslint plugins (e.g. eslint-plugin-tailwindcss, sort-imports) for better DX
- Add dockerized GitHub actions
- Create production-ready docker services
- Add ability to use custom top-level domain
